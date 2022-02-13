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

namespace NumberNine\Exception;

use NumberNine\Entity\UserRole;

final class UserRoleLockedException extends \RuntimeException
{
    public function __construct(UserRole $role)
    {
        parent::__construct(sprintf('User role "%s" is locked and cannot be deleted.', $role->getName()));
    }
}
