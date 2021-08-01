<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Content;

final class AreaStore
{
    private array $areas = [];

    public function getArea(string $id): ?array
    {
        return $this->areas[$id] ?? null;
    }

    public function getAreas(): array
    {
        return $this->areas;
    }

    public function setAreas(array $areas): void
    {
        $this->areas = $areas;
    }
}
