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
use NumberNine\Event\UpdateContentEntityEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class UpdateContentEntityEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            UpdateContentEntityEvent::class => ['updateMediaFile', 1000],
        ];
    }

    public function updateMediaFile(UpdateContentEntityEvent $event): void
    {
        /** @var MediaFile $entity */
        $entity = $event->getEntity();

        if (!$entity instanceof MediaFile) {
            return;
        }

        /** @var array $data */
        $data = $event->getRequest()->request->all();

        $entity->setCaption($data['caption'] ?? null);
        $entity->setAlternativeText($data['alternativeText'] ?? null);
    }
}
