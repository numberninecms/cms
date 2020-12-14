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

use NumberNine\Entity\Post;
use NumberNine\Event\MainLoopQueryEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class MainLoopQueryEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            MainLoopQueryEvent::class => 'fetchJoinFeaturedImage'
        ];
    }

    public function fetchJoinFeaturedImage(MainLoopQueryEvent $event): void
    {
        if (
            $event->getContentType()->getEntityClassName() !== Post::class
            && !is_subclass_of($event->getContentType()->getEntityClassName(), Post::class)
        ) {
            return;
        }

        $event->getQueryBuilder()
            ->addSelect('featured_image')
            ->leftJoin('c.featuredImage', 'featured_image');
    }
}
