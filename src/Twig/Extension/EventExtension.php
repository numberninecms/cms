<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Twig\Extension;

use NumberNine\Event\Theme\AbstractThemeEvent;
use NumberNine\Event\Theme\FooterEvent;
use NumberNine\Event\Theme\HeadEvent;
use NumberNine\Exception\ThemeNotFoundException;
use NumberNine\Theme\ThemeEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class EventExtension extends AbstractExtension
{
    private EventDispatcherInterface $eventDispatcher;
    private ThemeEventDispatcher $themeEventDispatcher;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        ThemeEventDispatcher $themeEventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->themeEventDispatcher = $themeEventDispatcher;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            // Sections
            new TwigFunction('N9_head', [$this, 'head'], ['is_safe' => ['html']]),
            new TwigFunction('N9_footer', [$this, 'footer'], ['is_safe' => ['html']]),

            // Hook system
            new TwigFunction('N9_dispatch', [$this, 'dispatch'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('N9_filter', [$this, 'filter']),
        ];
    }

    /**
     * Renders the <head> section
     * @return string
     */
    public function head(): string
    {
        return (string)$this->eventDispatcher->dispatch(new HeadEvent());
    }

    /**
     * Renders the footer section
     * @return string
     */
    public function footer(): string
    {
        return (string)$this->eventDispatcher->dispatch(new FooterEvent());
    }

    /**
     * Dispatches a theme event
     * @param string $eventName Theme event
     * @return string
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
