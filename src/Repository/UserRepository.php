<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\QueryBuilder;
use InvalidArgumentException;
use NumberNine\Entity\Comment;
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\User;
use NumberNine\Model\Pagination\PaginationParameters;
use RuntimeException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class UserRepository extends ServiceEntityRepository
{
    public const DELETE_MODE_REASSIGN = 'reassign';
    public const DELETE_MODE_DELETE = 'delete';

    private TokenStorageInterface $tokenStorage;

    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, User::class);
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param string $fieldName
     * @param mixed $fieldValue
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function findOneByCustomField(string $fieldName, $fieldValue): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('JSON_EXTRACT(u.customFields, :fieldName) = :fieldValue')
            ->setParameters(
                [
                    'fieldName' => '$.' . $fieldName,
                    'fieldValue' => $fieldValue
                ]
            )
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $role
     * @return User[]
     */
    public function findByRole(string $role): array
    {
        return $this->createQueryBuilder('u')
            ->where('JSON_CONTAINS(u.roles, :role) = 1')
            ->setParameter('role', '"' . $role . '"')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param PaginationParameters $paginationParameters
     * @param Criteria|null $criteria
     * @return QueryBuilder
     * @throws QueryException
     */
    public function getPaginatedCollectionQueryBuilder(PaginationParameters $paginationParameters, Criteria $criteria = null): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->select('u', 'r')
            ->join('u.userRoles', 'r')
            ->setFirstResult($paginationParameters->getStartRow() ?: 0)
            ->setMaxResults($paginationParameters->getFetchCount() ?: PHP_INT_MAX);

        if ($paginationParameters->getFilter()) {
            $or = $queryBuilder->expr()->orx();
            $or->add($queryBuilder->expr()->like('u.id', ':filter'));
            $or->add($queryBuilder->expr()->like('u.username', ':filter'));
            $or->add($queryBuilder->expr()->like('u.email', ':filter'));

            $queryBuilder
                ->andWhere($or)
                ->setParameter('filter', '%' . trim((string)$paginationParameters->getFilter()) . '%');
        }

        if ($paginationParameters->getOrderBy()) {
            $entity = $paginationParameters->getOrderBy() === 'roles' ? 'r.' : 'u.';
            $queryBuilder->addOrderBy($entity . $paginationParameters->getOrderBy(), $paginationParameters->getOrder());
        }

        if ($criteria) {
            $queryBuilder->addCriteria($criteria);
        }

        return $queryBuilder;
    }

    public function deleteUsers(array $ids, string $associatedContent): void
    {
        if (!in_array($associatedContent, [self::DELETE_MODE_REASSIGN, self::DELETE_MODE_DELETE], true)) {
            throw new InvalidArgumentException(sprintf('Parameter $associatedContent value must be one of "%s" or "%s".', self::DELETE_MODE_REASSIGN, self::DELETE_MODE_DELETE));
        }

        /** @var ?User $currentUser */
        $currentUser = $this->tokenStorage->getToken() !== null ? $this->tokenStorage->getToken()->getUser() : null;

        if ($associatedContent === self::DELETE_MODE_REASSIGN && $currentUser === null) {
            throw new RuntimeException('No user to reassign to.');
        }

        $users = $this->createQueryBuilder('u')
            ->where('u.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->iterate();

        $counter = 0;

        foreach ($users as $row) {
            /** @var User $user */
            $user = $row[0];

            foreach ($user->getComments() as $comment) {
                /** @var CommentRepository $commentRepository */
                $commentRepository = $this->_em->getRepository(Comment::class);
                $commentRepository->removeCommentAuthor($comment);
            }

            if ($associatedContent === self::DELETE_MODE_REASSIGN) {
                foreach ($user->getContentEntities() as $contentEntity) {
                    /** @var ContentEntity $contentEntity */
                    $contentEntity->setAuthor($currentUser);
                    $this->_em->persist($contentEntity);
                }
            } elseif ($associatedContent === self::DELETE_MODE_DELETE) {
                $this->_em->getFilters()->disable('softdeleteable');
                $entities = $user->getContentEntities();

                foreach ($entities as $contentEntity) {
                    $user->removeContentEntity($contentEntity);
                    $this->_em->persist($contentEntity);
                }

                $this->_em->flush();

                foreach ($entities as $contentEntity) {
                    $this->_em->remove($contentEntity); // soft-delete
                }

                $this->_em->flush();
            }

            $this->_em->remove($user);

            if (++$counter % 100 === 0) {
                $this->_em->flush();
                $this->_em->clear();
            }
        }

        $this->_em->flush();
        $this->_em->clear();
    }
}
