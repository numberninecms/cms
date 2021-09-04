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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

final class UpdateContentEntityEvent extends Event
{
    public function __construct(private ContentEntity $entity, private Request $request)
    {
    }

    public function getEntity(): ContentEntity
    {
        return $this->entity;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
