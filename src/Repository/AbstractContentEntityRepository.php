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
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\ContentEntityRelationship;
use NumberNine\Entity\Term;
use NumberNine\Model\Pagination\PaginationParameters;

/**
 * @method ContentEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContentEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContentEntity[]    findAll()
 * @method ContentEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
abstract class AbstractContentEntityRepository extends ServiceEntityRepository
{
    use FindByCustomFieldTrait;

    public function getPaginatedCollectionQueryBuilder(
        string $contentType,
        PaginationParameters $paginationParameters,
        Criteria $criteria = null
    ): QueryBuilder {
        $queryBuilder = $this->createQueryBuilder('c')
            ->select('c', 'a', 'cet_fetch_join', 't_fetch_join')
            ->leftJoin('c.author', 'a')
            ->leftJoin('c.contentEntityTerms', 'cet_fetch_join')
            ->leftJoin('cet_fetch_join.term', 't_fetch_join')
            ->where('c.customType = :customType')
            ->setParameter('customType', $contentType)
            ->setFirstResult($paginationParameters->getStartRow() ?: 0)
            ->setMaxResults($paginationParameters->getFetchCount() ?: PHP_INT_MAX)
        ;

        if ($paginationParameters->getStatus()) {
            $status = explode(',', (string) $paginationParameters->getStatus());
            $deleted = false;

            if (\in_array('deleted', $status, true)) {
                $deleted = true;
                unset($status[array_search('deleted', $status, true)]);
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
        }

        if (!empty($paginationParameters->getTerms())) {
            $queryBuilder
                ->join('c.contentEntityTerms', 'cet')
                ->join('cet.term', 't')
                ->andWhere('t.id IN (:termIds)')
                ->setParameter(
                    'termIds',
                    array_reduce(
                        $paginationParameters->getTerms(),
                        static function ($array, Term $term) {
                            $array[] = $term->getId();

                            return $array;
                        }
                    )
                )
            ;
        }

        if ($paginationParameters->getFilter()) {
            $or = $queryBuilder->expr()->orx();
            $or->add($queryBuilder->expr()->like('c.id', ':filter'));
            $or->add($queryBuilder->expr()->like('c.title', ':filter'));
            $or->add($queryBuilder->expr()->like('c.slug', ':filter'));
            $or->add($queryBuilder->expr()->like('a.username', ':filter'));

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

    public function getSimplePaginatedCollectionQueryBuilder(
        string $contentType,
        int $startRow = 0,
        int $fetchCount = PHP_INT_MAX
    ): QueryBuilder {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.customType = :customType')
            ->orderBy('c.title', 'asc')
            ->setParameter('customType', $contentType)
            ->setFirstResult($startRow)
            ->setMaxResults($fetchCount)
        ;
    }

    public function removeCollection(array $ids): void
    {
        if ($this->_em->getFilters()->isEnabled('softdeleteable')) {
            $this->_em->getFilters()->disable('softdeleteable');
        }

        $entities = $this->findBy(['id' => $ids]);

        foreach ($entities as $entity) {
            $this->removeEntity($entity);
        }
    }

    public function restoreCollection(array $ids): void
    {
        if ($this->_em->getFilters()->isEnabled('softdeleteable')) {
            $this->_em->getFilters()->disable('softdeleteable');
        }

        $entities = $this->findBy(['id' => $ids]);

        foreach ($entities as $entity) {
            $entity->setDeletedAt(null);
        }
    }

    public function hardDeleteAllDeleted(string $type): void
    {
        if ($this->_em->getFilters()->isEnabled('softdeleteable')) {
            $this->_em->getFilters()->disable('softdeleteable');
        }

        $entities = $this->createQueryBuilder('c')
            ->where('c.deletedAt IS NOT NULL')
            ->andWhere('c.customType = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->toIterable()
        ;

        $counter = 0;

        foreach ($entities as $row) {
            $this->removeEntity($row[0]);

            if (++$counter % 100 === 0) {
                $this->_em->flush();
                $this->_em->clear();
            }
        }

        $this->_em->flush();
        $this->_em->clear();
    }

    public function findOneByRelationship(ContentEntity $entity, string $relationshipName): ?ContentEntity
    {
        $relationship = $this->_em->getRepository(ContentEntityRelationship::class)->findOneBy([
            'parent' => $entity->getId(),
            'name' => $relationshipName,
        ]);

        return $relationship ? $relationship->getChild() : null;
    }

    private function removeEntity(ContentEntity $entity): void
    {
        if ($entity->getDeletedAt() !== null) {
            foreach ($entity->getContentEntityTerms() as $contentEntityTerm) {
                $this->_em->remove($contentEntityTerm);
            }

            foreach ($entity->getComments() as $comment) {
                $this->_em->remove($comment);
            }

            $relationships = $this->_em->getRepository(ContentEntityRelationship::class)->createQueryBuilder('r')
                ->where('r.parent = :id')
                ->orWhere('r.child = :id')
                ->setParameter('id', $entity->getId())
                ->getQuery()
                ->getResult()
            ;

            foreach ($relationships as $relationship) {
                $this->_em->remove($relationship);
            }
        }

        $this->_em->remove($entity);
    }
}
