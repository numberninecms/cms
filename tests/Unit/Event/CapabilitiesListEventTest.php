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

namespace NumberNine\Tests\Unit\Event;

use NumberNine\Event\CapabilitiesListEvent;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class CapabilitiesListEventTest extends TestCase
{
    public function testInitializeWithCapabilities(): void
    {
        $event = new CapabilitiesListEvent(['cap1', 'cap2']);
        static::assertEquals(['cap1', 'cap2'], $event->getCapabilities());
    }

    public function testAddOneCapability(): void
    {
        $event = new CapabilitiesListEvent(['cap1']);
        $event->addCapability('cap2');
        static::assertEquals(['cap1', 'cap2'], $event->getCapabilities());
    }

    public function testAddMultipleCapabilities(): void
    {
        $event = new CapabilitiesListEvent(['cap1']);
        $event->addCapabilities(['cap2', 'cap3']);
        static::assertEquals(['cap1', 'cap2', 'cap3'], $event->getCapabilities());
    }
}
