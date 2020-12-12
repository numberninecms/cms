<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Event;

use Doctrine\ORM\QueryBuilder;
use NumberNine\Model\Content\ContentType;
use Symfony\Contracts\EventDispatcher\Event;

final class MainLoopQueryEvent extends Event
{
    private QueryBuilder $queryBuilder;
    private ContentType $contentType;

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder, ContentType $contentType)
    {
        $this->queryBuilder = $queryBuilder;
        $this->contentType = $contentType;
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }

    public function getContentType(): ContentType
    {
        return $this->contentType;
    }
}
