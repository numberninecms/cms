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

namespace NumberNine\Twig\Extension;

use NumberNine\Entity\ContentEntity;
use NumberNine\Event\SupportedContentEntityRelationshipsEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Twig\Extension\RuntimeExtensionInterface;

final class RelationshipRuntime implements RuntimeExtensionInterface
{
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function hasRelationship(ContentEntity $entity, string $relationshipName): bool
    {
        /** @var SupportedContentEntityRelationshipsEvent $event */
        $event = $this->eventDispatcher->dispatch(new SupportedContentEntityRelationshipsEvent(\get_class($entity)));

        return \in_array($relationshipName, $event->getRelationships());
    }
}
