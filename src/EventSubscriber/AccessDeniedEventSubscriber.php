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

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AccessDeniedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => 'handleAccessDenied',
        ];
    }

    public function handleAccessDenied(ExceptionEvent $event): void
    {
        if (!$event->getThrowable() instanceof AccessDeniedHttpException) {
            return;
        }

        $event->setResponse(new RedirectResponse($this->urlGenerator->generate('numbernine_homepage')));
    }
}
