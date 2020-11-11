<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Component\Features;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

trait EventDispatcherTrait
{
    protected EventDispatcherInterface $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    final public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }
}
