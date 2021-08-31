<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Theme;

use NumberNine\Event\Theme\AbstractThemeEvent;
use NumberNine\Event\Theme\ThemeEventInterface;
use NumberNine\Exception\ThemeEventNotFoundException;
use NumberNine\Model\Theme\ThemeInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class ThemeEventDispatcher
{
    private EventDispatcherInterface $eventDispatcher;
    private ThemeStore $themeStore;

    public function __construct(EventDispatcherInterface $eventDispatcher, ThemeStore $themeStore)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->themeStore = $themeStore;
    }

    /**
     * @param string $eventName
     * @param mixed|null $value
     * @return object
     */
    public function dispatch(string $eventName, $value = null): object
    {
        $event = $this->getThemeEvent($this->themeStore->getCurrentTheme(), $eventName, $value);

        if ($event === null) {
            throw new ThemeEventNotFoundException($eventName);
        }

        return $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param ThemeInterface $theme
     * @param string $eventName
     * @param mixed|null $value
     * @return ThemeEventInterface|null
     */
    private function getThemeEvent(ThemeInterface $theme, string $eventName, $value = null): ?ThemeEventInterface
    {
        $event = null;

        if ($parent = $theme->getParent()) {
            $event = $this->getThemeEvent($parent, $eventName);
        }

        try {
            $classes = get_declared_classes();
            $reflectTheme = new ReflectionClass($theme);

            foreach ($classes as $class) {
                $reflect = new ReflectionClass($class);

                // Only parse the theme's own namespace
                if (strpos($reflect->getNamespaceName(), $reflectTheme->getNamespaceName()) === false) {
                    continue;
                }

                $candidates = [
                    sprintf('%s\\Event\\%s', $reflect->getNamespaceName(), $eventName),
                    sprintf('%s\\Event\\%sEvent', $reflect->getNamespaceName(), $eventName),
                ];

                foreach ($candidates as $candidate) {
                    if (class_exists($candidate)) {
                        $event = $value ? new $candidate($value) : new $candidate();
                        break 2;
                    }
                }
            }
        } catch (ReflectionException $e) {
        }

        return $event;
    }
}
