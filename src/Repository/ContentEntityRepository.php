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
use NumberNine\Entity\ContentEntity;

/**
 * @method ContentEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContentEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContentEntity[]    findAll()
 * @method ContentEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ContentEntityRepository extends AbstractContentEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContentEntity::class);
    }

    public function findOneForEdition(int $id): ContentEntity
    {
        return $this->createQueryBuilder('e')
            ->addSelect('cet')
            ->leftJoin('e.contentEntityTerms', 'cet')
            ->where('e.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    /**
     * @return ContentEntity[]
     */
    public function findChildrenContentEntitiesOfType(ContentEntity $contentEntity, string $customType): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.parents', 'p')
            ->where('c.customType = :type')
            ->andWhere('p.id = :id')
            ->setParameters([
                'type' => $customType,
                'id' => $contentEntity->getId(),
            ])
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return string[]
     */
    public function findExistingCustomFieldsNames(): array
    {
        $result = $this->createQueryBuilder('c')
            ->select('JSON_KEYS(c.customFields) AS custom_fields')
            ->groupBy('custom_fields')
            ->getQuery()
            ->getResult()
        ;

        return array_unique(
            array_merge(
                ...array_map(fn ($json) => json_decode($json) ?? [], array_column($result, 'custom_fields'))
            )
        );
    }
}
