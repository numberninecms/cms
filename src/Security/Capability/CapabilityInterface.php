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

use NumberNine\Entity\User;

interface CapabilityInterface
{
    public function getName(): string;

    /**
     * @param mixed $subject
     * @param ?User $user
     *
     * @return int either ACCESS_GRANTED, ACCESS_ABSTAIN, or ACCESS_DENIED
     */
    public function handle($subject, ?User $user): int;
}
