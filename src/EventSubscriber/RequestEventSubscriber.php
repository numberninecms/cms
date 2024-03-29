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

use NumberNine\Command\AreaAwareCommandInterface;
use NumberNine\Command\ContentTypeAwareCommandInterface;
use NumberNine\Command\ImageSizeAwareCommandInterface;
use NumberNine\Command\RouteAwareCommandInterface;
use NumberNine\Content\AreaStore;
use NumberNine\Content\ContentService;
use NumberNine\Event\AreasRegistrationEvent;
use NumberNine\Event\ContentTypeRegistrationEvent;
use NumberNine\Event\ImageSizesEvent;
use NumberNine\Event\RouteRegistrationEvent;
use NumberNine\Media\ImageSizeStore;
use NumberNine\Routing\RouteProvider;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

final class RequestEventSubscriber implements EventSubscriberInterface, ServiceSubscriberInterface
{
    use ServiceSubscriberTrait;

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
            ConsoleCommandEvent::class => $listeners,
        ];
    }

    public function registerContentTypes(RequestEvent|ConsoleCommandEvent $event): void
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

    public function registerImageSizes(RequestEvent|ConsoleCommandEvent $event): void
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
     * Registers dynamic routes.
     */
    public function registerRoutes(RequestEvent|ConsoleCommandEvent $event): void
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

        $routeProvider = $this->routeProvider();

        foreach ($routeRegistrationEvent->getRoutes() as $name => $route) {
            $routeProvider->addRoute($name, $route);
        }
    }

    /**
     * Registers areas.
     */
    public function registerAreas(RequestEvent|ConsoleCommandEvent $event): void
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

    #[SubscribedService]
    private function eventDispatcher(): EventDispatcherInterface
    {
        return $this->container->get(__METHOD__);
    }

    #[SubscribedService]
    private function contentService(): ContentService
    {
        return $this->container->get(__METHOD__);
    }

    #[SubscribedService]
    private function imageSizeStore(): ImageSizeStore
    {
        return $this->container->get(__METHOD__);
    }

    /**
     * @return RouteProvider
     */
    #[SubscribedService]
    private function routeProvider(): RouteProviderInterface
    {
        return $this->container->get(__METHOD__);
    }

    #[SubscribedService]
    private function areaStore(): AreaStore
    {
        return $this->container->get(__METHOD__);
    }
}
