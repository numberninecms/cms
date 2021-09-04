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

use NumberNine\Controller\Frontend\Content\ContentEntityIndexAction;
use NumberNine\Event\ContentEntityShowForwardEvent;
use NumberNine\Model\General\Settings;
use NumberNine\Configuration\ConfigurationReadWriter;
use Psr\Cache\CacheException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class ContentEntityShowForwardEventSubscriber implements EventSubscriberInterface
{
    use ForwardRequestTrait;

    public static function getSubscribedEvents(): array
    {
        return [
            ContentEntityShowForwardEvent::class => 'forwardPageForPost'
        ];
    }

    public function __construct(private ConfigurationReadWriter $configurationReadWriter, private HttpKernelInterface $httpKernel)
    {
    }

    /**
     * @throws CacheException
     * @throws InvalidArgumentException
     */
    public function forwardPageForPost(ContentEntityShowForwardEvent $event): void
    {
        $pageForPosts = $this->configurationReadWriter->read(Settings::PAGE_FOR_POSTS);

        if ((int)$pageForPosts === $event->getEntity()->getId()) {
            $response = $this->getForwardResponse($event, ContentEntityIndexAction::class);
            $event->setResponse($response);
        }
    }
}
