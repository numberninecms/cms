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
use NumberNine\Entity\UserRole;
use NumberNine\Security\Capabilities;
use NumberNine\Security\Capability\CapabilityInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class CapabilityVoter implements VoterInterface
{
    /** @var CapabilityInterface[] */
    private array $capabilities;

    public function __construct(iterable $capabilities)
    {
        $capabilityNames = array_map(fn(CapabilityInterface $c): string => $c->getName(), [...$capabilities]);
        $this->capabilities = (array)array_combine($capabilityNames, [...$capabilities]);
    }

    public function vote(TokenInterface $token, $subject, array $attributes): int
    {
        $result = self::ACCESS_ABSTAIN;
        /** @var User $user */
        $user = $token->getUser() instanceof User ? $token->getUser() : null;

        if (!$user instanceof User) {
            return $result;
        }

        $userCapabilities = array_merge(...array_map(
            fn(UserRole $role): array => $role->getCapabilities(),
            $user->getUserRoles()->toArray()
        ));

        foreach ($attributes as $attribute) {
            $result = self::ACCESS_DENIED;

            if (in_array($attribute, $userCapabilities, true)) {
                if ($attribute === Capabilities::READ) { // handled by another ReaderVoter
                    continue;
                }

                if (array_key_exists($attribute, $this->capabilities)) {
                    return $this->capabilities[$attribute]->handle($subject, $user);
                }

                return self::ACCESS_GRANTED;
            }
        }

        return $result;
    }
}
