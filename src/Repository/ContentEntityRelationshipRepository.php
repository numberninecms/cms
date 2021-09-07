<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use NumberNine\Entity\ContentEntityRelationship;

/**
 * @method ContentEntityRelationship|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContentEntityRelationship|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContentEntityRelationship[]    findAll()
 * @method ContentEntityRelationship[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ContentEntityRelationshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContentEntityRelationship::class);
    }
}
