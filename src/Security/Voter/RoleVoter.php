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
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class RoleVoter implements VoterInterface
{
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        $result = VoterInterface::ACCESS_ABSTAIN;
        /** @var User $user */
        $user = $token->getUser() instanceof User ? $token->getUser() : null;

        if (!$user instanceof User) {
            return $result;
        }

        $roles = array_map(fn(UserRole $role): ?string => $role->getName(), $user->getUserRoles()->toArray());

        foreach ($attributes as $attribute) {
            $result = VoterInterface::ACCESS_DENIED;
            foreach ($roles as $role) {
                if ($attribute === $role) {
                    return VoterInterface::ACCESS_GRANTED;
                }
            }
        }

        return $result;
    }
}
