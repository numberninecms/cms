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

final class LoginPathsEvent extends Event
{
    /** @var string[] */
    private array $paths;

    /**
     * @param array|string[] $paths
     */
    public function __construct(array $paths = [])
    {
        $this->paths = array_unique($paths);
    }

    /**
     * @return string[]
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * @param string[] $paths
     */
    public function setPaths(array $paths): void
    {
        $this->paths = array_unique($paths);
    }

    public function addPath(string $path): void
    {
        $this->paths[] = $path;
        $this->paths = array_unique($this->paths);
    }
}
