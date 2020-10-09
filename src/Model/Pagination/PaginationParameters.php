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

    /**
     * @return int|null
     */
    public function getStartRow(): ?int
    {
        return $this->startRow;
    }

    /**
     * @param int|null $startRow
     * @return PaginationParameters
     */
    public function setStartRow($startRow): self
    {
        $this->startRow = (int)$startRow;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFetchCount(): ?int
    {
        return $this->fetchCount;
    }

    /**
     * @param int|null $fetchCount
     * @return PaginationParameters
     */
    public function setFetchCount($fetchCount): self
    {
        $this->fetchCount = (int)$fetchCount;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilter(): ?string
    {
        return $this->filter;
    }

    /**
     * @param string|null $filter
     * @return PaginationParameters
     */
    public function setFilter(?string $filter): self
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     * @return PaginationParameters
     */
    public function setStatus(?string $status): PaginationParameters
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    /**
     * @param string|null $orderBy
     * @return PaginationParameters
     */
    public function setOrderBy(?string $orderBy): self
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrder(): ?string
    {
        return $this->order;
    }

    /**
     * @param string|null $order
     * @return PaginationParameters
     */
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
     * @return PaginationParameters
     */
    public function setTerms(array $terms): self
    {
        $this->terms = $terms;
        return $this;
    }
}
