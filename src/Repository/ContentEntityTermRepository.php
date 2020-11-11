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

use Doctrine\Persistence\ManagerRegistry;
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

    // /**
    //  * @return ContentEntityTerm[] Returns an array of ContentEntityTerm objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContentEntityTerm
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
