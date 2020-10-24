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

use NumberNine\Command\ContentTypeAwareCommandInterface;
use NumberNine\Command\ImageSizeAwareCommandInterface;
use NumberNine\Command\RouteAwareCommandInterface;
use NumberNine\Command\AreaAwareCommandInterface;
use NumberNine\Event\ContentTypeRegistrationEvent;
use NumberNine\Event\ImageSizesEvent;
use NumberNine\Event\RouteRegistrationEvent;
use NumberNine\Event\AreasRegistrationEvent;
use NumberNine\Routing\RouteProvider;
use NumberNine\Content\ContentService;
use NumberNine\Content\AreaStore;
use NumberNine\Media\ImageSizeStore;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

final class RequestEventSubscriber implements EventSubscriberInterface, ServiceSubscriberInterface
{
    use ServiceSubscriberTrait;

    private function eventDispatcher(): EventDispatcherInterface
    {
        return $this->container->get(__METHOD__);
    }

    private function contentService(): ContentService
    {
        return $this->container->get(__METHOD__);
    }

    private function imageSizeStore(): ImageSizeStore
    {
        return $this->container->get(__METHOD__);
    }

    /**
     * @return RouteProvider
     */
    private function routeProvider(): RouteProviderInterface
    {
        return $this->container->get(__METHOD__);
    }

    private function AreaStore(): AreaStore
    {
        return $this->container->get(__METHOD__);
    }

    public static function getSubscribedEvents(): array
    {
        $listeners = [
            ['registerContentTypes', 500],
            ['registerImageSizes', 450],
            ['registerRoutes', 400],
            ['registerAreas', 350],
        ];

        return [
            RequestEvent::class => $listeners,
            ConsoleCommandEvent::class => $listeners
        ];
    }

    /**
     * @param RequestEvent|ConsoleCommandEvent $event
     */
    public function registerContentTypes($event): void
    {
        if (
            ($event instanceof ConsoleCommandEvent)
            && ($command = $event->getCommand())
            && !$command instanceof ContentTypeAwareCommandInterface
            && $command->getName() !== 'doctrine:fixtures:load'
        ) {
            return;
        }

        /** @var ContentTypeRegistrationEvent $contentTypeRegistrationEvent */
        $contentTypeRegistrationEvent = $this->eventDispatcher()->dispatch(new ContentTypeRegistrationEvent());
        $this->contentService()->setContentTypes($contentTypeRegistrationEvent->getContentTypes());
    }

    /**
     * @param RequestEvent|ConsoleCommandEvent $event
     */
    public function registerImageSizes($event): void
    {
        if (
            ($event instanceof ConsoleCommandEvent)
            && ($command = $event->getCommand())
            && !$command instanceof ImageSizeAwareCommandInterface
            && $command->getName() !== 'doctrine:fixtures:load'
        ) {
            return;
        }

        /** @var ImageSizesEvent $imageSizesEvent */
        $imageSizesEvent = $this->eventDispatcher()->dispatch(new ImageSizesEvent());
        $this->imageSizeStore()->setImageSizes($imageSizesEvent->getSizes());
    }

    /**
     * Registers dynamic routes
     * @param RequestEvent|ConsoleCommandEvent $event
     */
    public function registerRoutes($event): void
    {
        if (
            ($event instanceof ConsoleCommandEvent)
            && ($command = $event->getCommand())
            && !$command instanceof RouteAwareCommandInterface
            && $command->getName() !== 'doctrine:fixtures:load'
        ) {
            return;
        }

        /** @var RouteRegistrationEvent $routeRegistrationEvent */
        $routeRegistrationEvent = $this->eventDispatcher()->dispatch(new RouteRegistrationEvent());

        foreach ($routeRegistrationEvent->getRoutes() as $name => $route) {
            $this->routeProvider()->addRoute($name, $route);
        }
    }

    /**
     * Registers areas
     * @param RequestEvent|ConsoleCommandEvent $event
     */
    public function registerAreas($event): void
    {
        if (
            ($event instanceof ConsoleCommandEvent)
            && ($command = $event->getCommand())
            && !$command instanceof AreaAwareCommandInterface
            && $command->getName() !== 'doctrine:fixtures:load'
        ) {
            return;
        }

        /** @var AreasRegistrationEvent $areasRegistrationEvent */
        $areasRegistrationEvent = $this->eventDispatcher()->dispatch(new AreasRegistrationEvent());
        $this->areaStore()->setAreas($areasRegistrationEvent->getAreas());
    }
}
