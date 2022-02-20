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
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccessDeniedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private RequestStack $requestStack,
        private string $adminUrlPrefix,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => ['handleAccessDenied', 100],
        ];
    }

    public function handleAccessDenied(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof AccessDeniedException && !$exception instanceof AccessDeniedHttpException) {
            return;
        }

        $isAdminUrl = str_starts_with(
            $this->requestStack->getCurrentRequest()->getPathInfo(),
            sprintf('/%s/', $this->adminUrlPrefix),
        );

        if ($isAdminUrl) {
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('numbernine_login')));
        } else {
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('numbernine_homepage')));
        }
    }
}
