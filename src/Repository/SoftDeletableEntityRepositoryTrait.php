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

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use NumberNine\Exception\NotImplementedException;
use NumberNine\Model\Content\SoftDeletableEntity;

/**
 * @property EntityManagerInterface $_em
 *
 * @method SoftDeletableEntity[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method QueryBuilder          createQueryBuilder($alias, $indexBy = null)
 */
trait SoftDeletableEntityRepositoryTrait
{
    public function removeCollection(array $ids, bool $flush = true): void
    {
        if ($this->_em->getFilters()->isEnabled('softdeleteable')) {
            $this->_em->getFilters()->disable('softdeleteable');
        }

        foreach ($this->findBy(['id' => $ids]) as $entity) {
            $this->removeEntity($entity);
        }

        if ($flush) {
            $this->_em->flush();
        }
    }

    public function restoreCollection(array $ids, bool $flush = true): void
    {
        if ($this->_em->getFilters()->isEnabled('softdeleteable')) {
            $this->_em->getFilters()->disable('softdeleteable');
        }

        foreach ($this->findBy(['id' => $ids]) as $entity) {
            $entity->setDeletedAt(null);
        }

        if ($flush) {
            $this->_em->flush();
        }
    }

    public function emptyTrash(?Criteria $criteria = null): void
    {
        if ($this->_em->getFilters()->isEnabled('softdeleteable')) {
            $this->_em->getFilters()->disable('softdeleteable');
        }

        $queryBuilder = $this->createQueryBuilder('c')->where('c.deletedAt IS NOT NULL');

        if ($criteria) {
            $queryBuilder->addCriteria($criteria);
        }

        $entities = $queryBuilder->getQuery()->toIterable();
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

    protected function removeEntity(mixed $entity): void
    {
        throw new NotImplementedException();
    }
}
