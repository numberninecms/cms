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

use NumberNine\Entity\Taxonomy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use NumberNine\Model\Content\ContentType;

/**
 * @method Taxonomy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Taxonomy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Taxonomy[]    findAll()
 * @method Taxonomy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class TaxonomyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Taxonomy::class);
    }

    /**
     * @param ContentType $contentType
     * @return Taxonomy[]
     */
    public function findByContentType(ContentType $contentType): array
    {
        return $this->createQueryBuilder('t')
            ->where("JSON_SEARCH(t.contentTypes, 'one', :contentType) IS NOT NULL")
            ->andWhere('t.contentTypes IS NOT NULL')
            ->setParameter('contentType', $contentType->getName())
            ->getQuery()
            ->getResult();
    }
}
