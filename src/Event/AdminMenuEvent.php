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

use NumberNine\Model\Menu\Builder\AdminMenuBuilder;
use Symfony\Contracts\EventDispatcher\Event;

final class AdminMenuEvent extends Event
{
    /**
     * AdminMenuEvent constructor.
     */
    public function __construct(private AdminMenuBuilder $builder)
    {
    }

    public function getBuilder(): AdminMenuBuilder
    {
        return $this->builder;
    }
}
