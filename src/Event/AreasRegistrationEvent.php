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

use Symfony\Contracts\EventDispatcher\Event;

final class AreasRegistrationEvent extends Event
{
    private array $areas = [];

    public function addArea(string $id, string $name): void
    {
        $this->areas[$id] = $name;
    }

    public function getAreas(): array
    {
        return $this->areas;
    }
}
