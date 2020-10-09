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
use Doctrine\ORM\QueryBuilder;
use NumberNine\Entity\ContentEntity;

trait FindByCustomFieldTrait
{
    /**
     * @param string $fieldName
     * @param mixed $fieldValue
     * @return array
     */
    public function findByCustomField(string $fieldName, $fieldValue): array
    {
        return $this->getByCustomFieldQueryBuilder($fieldName, $fieldValue)
            ->getQuery()
            ->useQueryCache(true)
            ->enableResultCache()
            ->getResult();
    }

    /**
     * @param string $fieldName
     * @param mixed $fieldValue
     * @return ContentEntity|null
     * @throws NonUniqueResultException
     */
    public function findOneByCustomField(string $fieldName, $fieldValue): ?ContentEntity
    {
        return $this->getByCustomFieldQueryBuilder($fieldName, $fieldValue)
            ->getQuery()
            ->useQueryCache(true)
            ->enableResultCache()
            ->getOneOrNullResult();
    }

    /**
     * @param string $fieldName
     * @param mixed $fieldValue
     * @return QueryBuilder
     */
    private function getByCustomFieldQueryBuilder(string $fieldName, $fieldValue): QueryBuilder
    {
        $expression = is_array($fieldValue) ? 'IN (:fieldValue)' : '= :fieldValue';

        return $this->createQueryBuilder('c')
            ->where('JSON_EXTRACT(c.customFields, :fieldName) ' . $expression)
            ->setParameters(
                [
                    'fieldName' => '$.' . $fieldName,
                    'fieldValue' => $fieldValue
                ]
            );
    }
}
