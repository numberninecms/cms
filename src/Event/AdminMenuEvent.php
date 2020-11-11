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
    /** @var AdminMenuBuilder */
    private $builder;

    /**
     * AdminMenuEvent constructor.
     * @param AdminMenuBuilder $builder
     */
    public function __construct(AdminMenuBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @return AdminMenuBuilder
     */
    public function getBuilder(): AdminMenuBuilder
    {
        return $this->builder;
    }
}
