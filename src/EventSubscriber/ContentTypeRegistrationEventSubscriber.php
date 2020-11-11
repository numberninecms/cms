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

use NumberNine\Entity\MediaFile;
use NumberNine\Entity\Post;
use NumberNine\Event\ContentTypeRegistrationEvent;
use NumberNine\Model\Content\ContentType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ContentTypeRegistrationEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ContentTypeRegistrationEvent::class => 'registerContentTypes'
        ];
    }

    public function registerContentTypes(ContentTypeRegistrationEvent $event): void
    {
        $event->addContentType(
            new ContentType(
                [
                    'name' => 'post',
                    'entity_class_name' => Post::class,
                    'permalink' => '/{year}/{month}/{day}/{slug}',
                ]
            )
        );

        $event->addContentType(
            new ContentType(
                [
                    'name' => 'page',
                    'entity_class_name' => Post::class,
                ]
            )
        );

        $event->addContentType(
            new ContentType(
                [
                    'name' => 'block',
                    'entity_class_name' => Post::class,
                    'permalink' => '/blocks/{slug}',
                    'icon' => 'view_day',
                    'public' => false,
                ]
            )
        );

        $event->addContentType(
            new ContentType(
                [
                    'name' => 'media_file',
                    'entity_class_name' => MediaFile::class,
                    'permalink' => '/media/{slug}',
                    'icon' => 'photo_library',
                    'shown_in_menu' => false,
                ]
            )
        );
    }
}
