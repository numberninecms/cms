<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Admin;

use NumberNine\Model\Menu\Builder\AdminMenuBuilder;

final class AdminMenuBuilderStore
{
    private AdminMenuBuilder $adminMenuBuilder;

    public function getAdminMenuBuilder(): AdminMenuBuilder
    {
        return $this->adminMenuBuilder;
    }

    public function setAdminMenuBuilder(AdminMenuBuilder $adminMenuBuilder): void
    {
        $this->adminMenuBuilder = $adminMenuBuilder;
    }
}
