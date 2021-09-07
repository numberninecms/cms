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

use NumberNine\Event\Theme\ThemeEventInterface;
use NumberNine\Exception\ThemeEventNotFoundException;
use NumberNine\Model\Theme\ThemeInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class ThemeEventDispatcher
{
    public function __construct(private EventDispatcherInterface $eventDispatcher, private ThemeStore $themeStore)
    {
    }

    /**
     * @param mixed|null $value
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
     * @param mixed|null $value
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
                if (!str_contains($reflect->getNamespaceName(), $reflectTheme->getNamespaceName())) {
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
        } catch (ReflectionException) {
        }

        return $event;
    }
}
