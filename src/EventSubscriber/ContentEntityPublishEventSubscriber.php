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

use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use NumberNine\Entity\ContentEntity;
use NumberNine\Model\Content\PublishingStatusInterface;

final class ContentEntityPublishEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [Events::prePersist, Events::preUpdate];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->update($args);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->update($args);
    }

    private function update(LifecycleEventArgs $args): void
    {
        /** @var ContentEntity $entity */
        $entity = $args->getEntity();

        if (
            $entity instanceof ContentEntity
            && $entity->getStatus() === PublishingStatusInterface::STATUS_PUBLISH
            && $entity->getPublishedAt() === null
        ) {
            $entity->setPublishedAt(new DateTime());
        }
    }
}
