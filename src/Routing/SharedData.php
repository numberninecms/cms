<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Routing;

use Symfony\Component\Routing\RouteCollection;

/**
 * This object that can be used to share data between different loaders.
 */
final class SharedData
{
    /** @var RouteCollection[] */
    private array $routes = [];

    public function getRoutes(string $resource): ?RouteCollection
    {
        return $this->routes[$resource] ?? null;
    }

    public function setRoutes(string $resource, RouteCollection $routes): void
    {
        $this->routes[$resource] = $routes;
    }
}
