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

namespace NumberNine\Twig\Extension;

use NumberNine\Event\Theme\AbstractThemeEvent;
use NumberNine\Event\Theme\FooterEvent;
use NumberNine\Event\Theme\HeadEvent;
use NumberNine\Exception\ThemeNotFoundException;
use NumberNine\Theme\ThemeEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Twig\Extension\RuntimeExtensionInterface;

final class EventRuntime implements RuntimeExtensionInterface
{
    public function __construct(private EventDispatcherInterface $eventDispatcher, private ThemeEventDispatcher $themeEventDispatcher)
    {
    }

    /**
     * Renders the <head> section
     */
    public function head(): string
    {
        return (string)$this->eventDispatcher->dispatch(new HeadEvent());
    }

    /**
     * Renders the footer section
     */
    public function footer(): string
    {
        return (string)$this->eventDispatcher->dispatch(new FooterEvent());
    }

    /**
     * Dispatches a theme event
     * @param string $eventName Theme event
     * @throws ThemeNotFoundException
     */
    public function dispatch(string $eventName): string
    {
        /** @var AbstractThemeEvent $event */
        $event = $this->themeEventDispatcher->dispatch($eventName);

        return $event->getObject();
    }

    /**
     * Filters an object
     * @param string $eventName Theme event
     * @param mixed $object Object to filter
     * @return mixed Filtered object
     * @throws ThemeNotFoundException
     */
    public function filter($object, string $eventName)
    {
        /** @var AbstractThemeEvent $event */
        $event = $this->themeEventDispatcher->dispatch($eventName, $object);

        return $event->getObject();
    }
}
