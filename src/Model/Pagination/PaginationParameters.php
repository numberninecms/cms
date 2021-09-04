<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Pagination;

use NumberNine\Entity\Term;

final class PaginationParameters
{
    /** @var int|null */
    private $startRow;

    /** @var int|null */
    private $fetchCount;

    /** @var string|null */
    private $filter;

    /** @var string|null */
    private $status;

    /** @var string|null */
    private $orderBy;

    /** @var string|null */
    private $order;

    /** @var Term[] */
    private $terms = [];

    public function getStartRow(): ?int
    {
        return $this->startRow;
    }

    /**
     * @param int|null $startRow
     */
    public function setStartRow($startRow): self
    {
        $this->startRow = (int)$startRow;
        return $this;
    }

    public function getFetchCount(): ?int
    {
        return $this->fetchCount;
    }

    /**
     * @param int|null $fetchCount
     */
    public function setFetchCount($fetchCount): self
    {
        $this->fetchCount = (int)$fetchCount;
        return $this;
    }

    public function getFilter(): ?string
    {
        return $this->filter;
    }

    public function setFilter(?string $filter): self
    {
        $this->filter = $filter;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): PaginationParameters
    {
        $this->status = $status;
        return $this;
    }

    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    public function setOrderBy(?string $orderBy): self
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    public function getOrder(): ?string
    {
        return $this->order;
    }

    public function setOrder(?string $order): self
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return Term[]
     */
    public function getTerms(): array
    {
        return $this->terms;
    }

    /**
     * @param Term[] $terms
     */
    public function setTerms(array $terms): self
    {
        $this->terms = $terms;
        return $this;
    }
}
