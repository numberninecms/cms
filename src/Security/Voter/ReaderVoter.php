<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Security\Voter;

use NumberNine\Entity\User;
use NumberNine\Security\Capabilities;
use NumberNine\Security\Capability\CapabilityInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class ReaderVoter extends Voter
{
    private CapabilityInterface $readCapability;

    public function __construct(iterable $capabilities)
    {
        $this->readCapability = array_filter(
            [...$capabilities],
            fn(CapabilityInterface $c) => $c->getName() === Capabilities::READ
        )[0];
    }

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === Capabilities::READ;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var ?User $user */
        $user = $token->getUser() instanceof User ? $token->getUser() : null;

        return $this->readCapability->handle($subject, $user) === VoterInterface::ACCESS_GRANTED;
    }
}
