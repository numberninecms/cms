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
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use NumberNine\Entity\MediaFile;
use NumberNine\Exception\FileNotDeletedException;

final class MediaFileDeletionEventSubscriber implements EventSubscriber
{
    private string $publicPath;

    public function __construct(string $publicPath)
    {
        $this->publicPath = $publicPath;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postRemove
        ];
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof MediaFile) {
            return;
        }

        $paths = array_merge($entity->getAllAssociatedFilePaths(), [$entity->getPath()]);

        foreach ($paths as $path) {
            $fullPath = $this->publicPath . $path;

            if (@unlink($fullPath) === false) {
                throw new FileNotDeletedException($fullPath);
            }
        }
    }
}
