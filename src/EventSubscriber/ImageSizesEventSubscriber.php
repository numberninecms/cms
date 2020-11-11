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

use NumberNine\Event\ImageSizesEvent;
use NumberNine\Model\Media\ImageSize;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ImageSizesEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ImageSizesEvent::class => 'registerImageSizes'
        ];
    }

    /**
     * @param ImageSizesEvent $event
     */
    public function registerImageSizes(ImageSizesEvent $event): void
    {
        $event
            ->addSize('thumbnail', new ImageSize(150, 150, true))
            ->addSize('preview', new ImageSize(300, 300))
            ->addSize('medium', new ImageSize(512, 512))
            ->addSize('large', new ImageSize(1024, 1024));
    }
}
