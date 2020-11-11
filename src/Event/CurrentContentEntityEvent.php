<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Event;

use NumberNine\Entity\ContentEntity;
use Symfony\Contracts\EventDispatcher\Event;

final class CurrentContentEntityEvent extends Event
{
    private ContentEntity $contentEntity;

    public function __construct(ContentEntity $contentEntity)
    {
        $this->contentEntity = $contentEntity;
    }

    public function getContentEntity(): ContentEntity
    {
        return $this->contentEntity;
    }
}
