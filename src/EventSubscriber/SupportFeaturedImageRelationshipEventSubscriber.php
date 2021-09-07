<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\EventSubscriber;

use NumberNine\Entity\Post;
use NumberNine\Event\SupportedContentEntityRelationshipsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SupportFeaturedImageRelationshipEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            SupportedContentEntityRelationshipsEvent::class => 'allowPosts',
        ];
    }

    public function allowPosts(SupportedContentEntityRelationshipsEvent $event): void
    {
        if ($event->getClassName() === Post::class) {
            $event->addRelationship('featured_image');
        }
    }
}
