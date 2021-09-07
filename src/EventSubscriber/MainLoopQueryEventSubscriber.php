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

use Doctrine\ORM\Query\Expr\Join;
use NumberNine\Event\MainLoopQueryEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class MainLoopQueryEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            MainLoopQueryEvent::class => 'fetchJoinFeaturedImage',
        ];
    }

    public function fetchJoinFeaturedImage(MainLoopQueryEvent $event): void
    {
        $event
            ->getQueryBuilder()
            ->addSelect('children', 'featured_image')
            ->leftJoin('c.children', 'children', Join::WITH, "children.name = 'featured_image'")
            ->leftJoin('children.child', 'featured_image')
        ;
    }
}
