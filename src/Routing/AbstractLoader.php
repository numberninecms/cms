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

use NumberNine\Event\RouteCollectionEvent;
use Oro\Component\Routing\Loader\CumulativeRoutingFileLoader;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouteCollection;

abstract class AbstractLoader extends CumulativeRoutingFileLoader
{
    protected ?EventDispatcherInterface $eventDispatcher;
    protected ?SharedData $cache;

    /**
     * Sets the event dispatcher
     *
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher = null): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Sets an object that can be used to share data between different loaders
     *
     * @param SharedData $cache
     */
    public function setCache(SharedData $cache = null): void
    {
        $this->cache = $cache;
    }

    /**
     * @param RouteCollection $routes
     * @return RouteCollection
     */
    protected function dispatchEvent(RouteCollection $routes): RouteCollection
    {
        if ($this->eventDispatcher === null) {
            return $routes;
        }

        $event = new RouteCollectionEvent($routes);
        $this->eventDispatcher->dispatch($event);

        return $event->getCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function loadRoutes(RouteCollection $routes): void
    {
        if ($this->cache === null) {
            parent::loadRoutes($routes);
        } else {
            $resources = $this->getResources();
            foreach ($resources as $resource) {
                if (is_string($resource)) {
                    $resourceRoutes = $this->cache->getRoutes($resource);
                    if (null === $resourceRoutes) {
                        $resourceRoutes = $this->resolve($resource)->load($resource);
                        $this->cache->setRoutes($resource, $resourceRoutes);
                    }
                } else {
                    $resourceRoutes = $this->resolve($resource)->load($resource);
                }
                $routes->addCollection($resourceRoutes);
            }
        }
    }
}
