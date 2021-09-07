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

namespace NumberNine\Repository;

use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\MediaFile;
use NumberNine\Exception\InvalidFeaturedImageEntityException;

trait FindFeaturedImageTrait
{
    public function findFeaturedImage(ContentEntity $contentEntity): ?MediaFile
    {
        $entity = $this->findOneByRelationship($contentEntity, 'featured_image');

        if ($entity && !$entity instanceof MediaFile) {
            throw new InvalidFeaturedImageEntityException($entity);
        }

        // @var ?MediaFile $entity
        return $entity;
    }
}
