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

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\ContentEntityTerm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ContentEntityTerm|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContentEntityTerm|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContentEntityTerm[]    findAll()
 * @method ContentEntityTerm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ContentEntityTermRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContentEntityTerm::class);
    }

    public function findByTaxonomyName(ContentEntity $entity, string $taxonomy): array
    {
        $queryBuilder = $this->createQueryBuilder('cet')
            ->addSelect('t')
            ->join('cet.term', 't')
            ->join('t.taxonomy', 'tax', Join::WITH, 'tax.name = :taxonomy')
            ->where('cet.contentEntity = :id')
            ->setParameters([
                'id' => $entity->getId(),
                'taxonomy' => $taxonomy,
            ])
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param array|(int|null)[] $contentEntityTermsIdsToRemove
     */
    public function removeCollectionByIds(array $contentEntityTermsIdsToRemove): void
    {
        $this->createQueryBuilder('cet')
            ->delete()
            ->where('cet.id IN (:ids)')
            ->setParameter('ids', $contentEntityTermsIdsToRemove)
            ->getQuery()
            ->execute()
        ;
    }
}
