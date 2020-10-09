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

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Contracts\EventDispatcher\Event;

final class PaginatorEvent extends Event
{
    private Paginator $paginator;

    public function __construct(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    public function getPaginator(): Paginator
    {
        return $this->paginator;
    }
}
