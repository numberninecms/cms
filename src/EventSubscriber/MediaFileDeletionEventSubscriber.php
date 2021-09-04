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

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use NumberNine\Entity\MediaFile;
use NumberNine\Exception\FileNotDeletedException;

final class MediaFileDeletionEventSubscriber implements EventSubscriber
{
    private array $deletedEntities;

    public function __construct(private string $publicPath)
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
            Events::postFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $this->deletedEntities = $uow->getScheduledEntityDeletions();
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        foreach ($this->deletedEntities as $entity) {
            if (!$entity instanceof MediaFile) {
                continue;
            }

            $paths = array_merge($entity->getAllAssociatedFilePaths(), [$entity->getPath()]);

            foreach ($paths as $path) {
                $fullPath = $this->publicPath . $path;

                if (file_exists($fullPath) && @unlink($fullPath) === false) {
                    throw new FileNotDeletedException($fullPath);
                }
            }
        }
    }
}
