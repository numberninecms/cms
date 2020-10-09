<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\EventSubscriber;

use NumberNine\Event\AreasRegistrationEvent;
use NumberNine\Model\Theme\ThemeInterface;
use NumberNine\Theme\ThemeStore;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class AreasRegistrationEventSubscriber implements EventSubscriberInterface
{
    private ThemeStore $themeStore;
    private EventDispatcherInterface $eventDispatcher;

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => ['registerCurrentThemeAreas', 4500],
            AreasRegistrationEvent::class => 'registerDefaultAreas',
        ];
    }

    public function __construct(ThemeStore $themeStore, EventDispatcherInterface $eventDispatcher)
    {
        $this->themeStore = $themeStore;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function registerCurrentThemeAreas(): void
    {
        $config = $this->themeStore->getCurrentTheme()->getConfiguration() ?? [];

        if (array_key_exists('areas', $config)) {
            $this->eventDispatcher->addListener(
                AreasRegistrationEvent::class,
                static function (AreasRegistrationEvent $event) use ($config): void {
                    foreach ($config['areas'] as $id => $name) {
                        $event->addArea($id, $name);
                    }
                }
            );
        }
    }

    public function registerDefaultAreas(AreasRegistrationEvent $event): void
    {
        $event->addArea('blog_sidebar', 'Blog sidebar');
    }
}
