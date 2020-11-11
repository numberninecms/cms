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

use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class RouteProvider implements RouteProviderInterface
{
    private RouteCollection $collection;

    /**
     * RouteProvider constructor.
     */
    public function __construct()
    {
        $this->collection = new RouteCollection();
    }


    public function addRoute(string $name, Route $route): void
    {
        $this->collection->add($name, $route);
    }

    public function getRouteCollectionForRequest(Request $request)
    {
        return $this->collection;
    }

    public function getRouteByName($name): Route
    {
        $route = $this->collection->get($name);

        if ($route === null) {
            throw new RouteNotFoundException(sprintf("Route '%s' not found.", $name));
        }

        return $route;
    }

    public function getRoutesByNames($names): array
    {
        $routes = [];

        if (empty($names)) {
            return $routes;
        }

        foreach ($names as $name) {
            if ($route = $this->collection->get($name)) {
                $routes[$name] = $route;
            }
        }

        return $routes;
    }
}
