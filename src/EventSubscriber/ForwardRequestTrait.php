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

use LogicException;
use NumberNine\Event\ContentEntityShowForwardEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Requires HttpKernelInterface injection
 */
trait ForwardRequestTrait
{
    protected function getForwardResponse(ContentEntityShowForwardEvent $event, string $controller, array $path = []): Response
    {
        if (!(property_exists($this, 'httpKernel') && $this->httpKernel instanceof HttpKernelInterface)) {
            throw new LogicException(sprintf('Class %s must inject HttpKernelInterface.', self::class));
        }

        $path['_controller'] ??= $controller;
        $path['type'] ??= $event->getEntity()->getCustomType();
        $subRequest = $event->getRequest()->duplicate([], null, $path);

        return $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }
}
