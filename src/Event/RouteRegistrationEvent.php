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

use Symfony\Component\Routing\Route;
use Symfony\Contracts\EventDispatcher\Event;

final class RouteRegistrationEvent extends Event
{
    /** @var Route[] */
    private array $routes = [];

    public function addRoute(string $name, Route $route): void
    {
        $this->routes[$name] = $route;
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
