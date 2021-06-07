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

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use NumberNine\Entity\Term;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use NumberNine\Model\Pagination\PaginationParameters;

/**
 * @method Term|null find($id, $lockMode = null, $lockVersion = null)
 * @method Term|null findOneBy(array $criteria, array $orderBy = null)
 * @method Term[]    findAll()
 * @method Term[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class TermRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Term::class);
    }

    /**
     * @param string $fieldName
     * @param mixed $fieldValue
     * @return Term|null
     * @throws NonUniqueResultException
     */
    public function findOneByCustomField(string $fieldName, $fieldValue): ?Term
    {
        return $this->createQueryBuilder('t')
            ->where('JSON_EXTRACT(t.customFields, :fieldName) = :fieldValue')
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
     * @param string $taxonomyName
     * @param bool $includeCount
     * @return Term[]
     */
    public function findByTaxonomyName(string $taxonomyName, bool $includeCount = false): array
    {
        return $this->findByTaxonomyNameQueryBuilder($taxonomyName, $includeCount)->getQuery()->getResult();
    }

    public function findByTaxonomyNameQueryBuilder(string $taxonomyName, bool $includeCount = false): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->join('t.taxonomy', 'tax', Join::WITH, 'tax.name = :taxonomy')
            ->orderBy('t.name', 'asc')
            ->setParameter('taxonomy', $taxonomyName);

        if ($includeCount) {
            $queryBuilder
                ->select('t as term', 'COUNT(c.id) as count')
                ->leftJoin('t.contentEntityTerms', 'cet')
                ->leftJoin('cet.contentEntity', 'c', Join::WITH, "c.status = 'publish'")
                ->groupBy('t.id');
        }

        return $queryBuilder;
    }

    public function getByTaxonomyPaginatedCollectionQueryBuilder(
        string $taxonomy,
        PaginationParameters $paginationParameters
    ): QueryBuilder {
        $queryBuilder = $this->createQueryBuilder('t')
            ->join('t.taxonomy', 'tax', Join::WITH, 'tax.name = :taxonomy')
            ->setParameter('taxonomy', $taxonomy)
            ->setFirstResult($paginationParameters->getStartRow() ?: 0)
            ->setMaxResults($paginationParameters->getFetchCount() ?: PHP_INT_MAX);

        if ($paginationParameters->getFilter()) {
            $or = $queryBuilder->expr()->orx();
            $or->add($queryBuilder->expr()->like('t.name', ':filter'));
            $or->add($queryBuilder->expr()->like('t.slug', ':filter'));
            $or->add($queryBuilder->expr()->like('t.description', ':filter'));

            $queryBuilder
                ->andWhere($or)
                ->setParameter('filter', '%' . trim((string)$paginationParameters->getFilter()) . '%');
        }

        if ($paginationParameters->getOrderBy()) {
            $queryBuilder->addOrderBy('t.' . $paginationParameters->getOrderBy(), $paginationParameters->getOrder());
        }

        return $queryBuilder;
    }

    /**
     * @param array $ids
     * @throws ORMException
     */
    public function removeCollection(array $ids): void
    {
        $terms = $this->findBy(['id' => $ids]);

        foreach ($terms as $term) {
            $this->_em->remove($term);
        }
    }
}
