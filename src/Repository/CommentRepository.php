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
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use NumberNine\Entity\Comment;
use NumberNine\Exception\UnknownCommentStatusException;
use NumberNine\Model\Content\CommentStatusInterface;
use NumberNine\Model\Pagination\PaginationParameters;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class CommentRepository extends ServiceEntityRepository
{
    use SoftDeletableEntityRepositoryTrait;

    public function __construct(ManagerRegistry $registry, private TranslatorInterface $translator)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findByContentEntityId(int $contentEntityId): array
    {
        return $this->createQueryBuilder('c')
            ->select('c', 'a')
            ->leftJoin('c.author', 'a')
            ->where('c.contentEntity = :contentEntityId')
            ->andWhere('c.parent IS NULL')
            ->andWhere('c.status IN (:status)')
            ->orderBy('c.createdAt', 'asc')
            ->setParameters([
                'contentEntityId' => $contentEntityId,
                'status' => [
                    CommentStatusInterface::COMMENT_STATUS_PENDING,
                    CommentStatusInterface::COMMENT_STATUS_APPROVED,
                ],
            ])
            ->getQuery()
            ->getResult()
        ;
    }

    public function removeCommentAuthor(Comment $comment): void
    {
        $comment
            ->setGuestAuthorName($this->translator->trans('Deleted user', [], 'numbernine'))
            ->setGuestAuthorEmail(null)
            ->setGuestAuthorUrl(null)
            ->setAuthor(null)
        ;
    }

    public function getPaginatedCollectionQueryBuilder(
        PaginationParameters $paginationParameters,
        Criteria $criteria = null
    ): QueryBuilder {
        $queryBuilder = $this->createQueryBuilder('c')
            ->select('c', 'ce', 'a')
            ->leftJoin('c.author', 'a')
            ->leftJoin('c.contentEntity', 'ce')
            ->setFirstResult($paginationParameters->getStartRow() ?: 0)
            ->setMaxResults($paginationParameters->getFetchCount() ?: PHP_INT_MAX)
        ;

        if ($paginationParameters->getStatus()) {
            $status = explode(',', (string) $paginationParameters->getStatus());
            $deleted = false;

            if (\in_array('deleted', $status, true)) {
                $deleted = true;
                unset($status[(int) array_search('deleted', $status, true)]);
            }

            if (!empty($status)) {
                $queryBuilder
                    ->andWhere('c.status IN (:status)')
                    ->setParameter('status', $status)
                ;
            }

            if ($deleted) {
                $this->_em->getFilters()->disable('softdeleteable');
                $queryBuilder->andWhere('c.deletedAt IS NOT NULL');
            }
        } else {
            $queryBuilder
                ->andWhere('c.status != :status')
                ->setParameter('status', CommentStatusInterface::COMMENT_STATUS_SPAM)
            ;
        }

        if ($paginationParameters->getFilter()) {
            $or = $queryBuilder->expr()->orx();
            $or->add($queryBuilder->expr()->like('c.id', ':filter'));
            $or->add($queryBuilder->expr()->like('c.content', ':filter'));
            $or->add($queryBuilder->expr()->like('a.username', ':filter'));
            $or->add($queryBuilder->expr()->like('a.email', ':filter'));
            $or->add($queryBuilder->expr()->like('c.guestAuthorName', ':filter'));
            $or->add($queryBuilder->expr()->like('c.guestAuthorEmail', ':filter'));

            $queryBuilder
                ->andWhere($or)
                ->setParameter('filter', '%' . trim((string) $paginationParameters->getFilter()) . '%')
            ;
        }

        if ($paginationParameters->getOrderBy()) {
            $entity = $paginationParameters->getOrderBy() === 'username' ? 'a.' : 'c.';
            $queryBuilder->addOrderBy($entity . $paginationParameters->getOrderBy(), $paginationParameters->getOrder());
        }

        if ($criteria) {
            $queryBuilder->addCriteria($criteria);
        }

        return $queryBuilder;
    }

    public function approveCollection(array $ids): void
    {
        $this->changeCollectionStatus($ids, CommentStatusInterface::COMMENT_STATUS_APPROVED);
    }

    public function unapproveCollection(array $ids): void
    {
        $this->changeCollectionStatus($ids, CommentStatusInterface::COMMENT_STATUS_PENDING);
    }

    public function markCollectionAsSpam(array $ids): void
    {
        $this->changeCollectionStatus($ids, CommentStatusInterface::COMMENT_STATUS_SPAM);
    }

    protected function removeEntity(Comment $entity): void
    {
        $this->_em->remove($entity);
    }

    private function changeCollectionStatus(array $ids, string $status): void
    {
        if (!\in_array($status, [
            CommentStatusInterface::COMMENT_STATUS_APPROVED,
            CommentStatusInterface::COMMENT_STATUS_PENDING,
            CommentStatusInterface::COMMENT_STATUS_SPAM,
        ], true)) {
            throw new UnknownCommentStatusException($status);
        }

        $this->createQueryBuilder('c')
            ->update()
            ->set('c.status', ':status')
            ->where('c.id IN (:ids)')
            ->setParameters(compact('ids', 'status'))
            ->getQuery()
            ->execute()
        ;
    }
}
