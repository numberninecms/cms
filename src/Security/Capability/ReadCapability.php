<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Security\Capability;

use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\User;
use NumberNine\Security\Capabilities;
use Symfony\Cmf\Bundle\RoutingBundle\Tests\Fixtures\App\Document\Content;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Inflector\EnglishInflector;

final class ReadCapability implements CapabilityInterface
{
    private Security $security;
    private EnglishInflector $inflector;

    public function __construct(Security $security)
    {
        $this->security = $security;
        $this->inflector = new EnglishInflector();
    }

    public function getName(): string
    {
        return Capabilities::READ;
    }

    public function handle($entity, ?User $user): int
    {
        if (!$entity instanceof ContentEntity) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if ($entity->isPublished()) {
            return VoterInterface::ACCESS_GRANTED;
        }

        $plural = current($this->inflector->pluralize($entity->getType()));

        if ($entity->isPrivate()) {
            if (
                $this->security->isGranted(sprintf('read_private_%s', $plural))
                || $entity->getAuthor() === $user
            ) {
                return VoterInterface::ACCESS_GRANTED;
            }

            return VoterInterface::ACCESS_DENIED;
        }

        if ($entity->isDraft()) {
            if ($this->security->isGranted(sprintf('edit_%s', $plural))) {
                return VoterInterface::ACCESS_GRANTED;
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }
}
