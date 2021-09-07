<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Pagination;

use ArrayIterator;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

final class Paginator implements IteratorAggregate
{
    private DoctrinePaginator $paginator;

    /** @var ArrayIterator|Traversable */
    private iterable $iterator;

    private int $firstResult;
    private int $maxResults;
    private int $totalItems;

    /**
     * @return never
     */
    public function __construct(DoctrinePaginator $paginator)
    {
        $query = $paginator->getQuery();

        if (($firstResult = $query->getFirstResult()) === null || ($maxResults = $query->getMaxResults()) === null) {
            throw new InvalidArgumentException(sprintf(
                '"%1$s::setFirstResult()" or/and "%1$s::setMaxResults()" was/were not applied to the query.',
                Query::class
            ));
        }

        $this->paginator = $paginator;
        $this->firstResult = (int) $firstResult;
        $this->maxResults = (int) $maxResults;
    }

    public function getCurrentPage(): int
    {
        if ($this->maxResults <= 0) {
            return 1;
        }

        return (int) (floor($this->firstResult / $this->maxResults) + 1);
    }

    public function getItemsPerPage(): int
    {
        return $this->maxResults;
    }

    public function getLastPage(): int
    {
        if ($this->maxResults <= 0) {
            return 1;
        }

        return (int) ceil($this->getTotalItems() / $this->maxResults) ?: 1;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems ?? $this->totalItems = \count($this->paginator);
    }

    public function getQuery(): Query
    {
        return $this->paginator->getQuery();
    }

    public function getIterator(): Traversable
    {
        return $this->iterator ?? $this->iterator = $this->paginator->getIterator();
    }

    public function count(): int
    {
        return iterator_count($this->getIterator());
    }
}
