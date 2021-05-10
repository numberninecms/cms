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

use NumberNine\Admin\AdminMenuBuilderStore;
use Twig\Extension\RuntimeExtensionInterface;

final class AdminRuntime implements RuntimeExtensionInterface
{
    private AdminMenuBuilderStore $adminMenuBuilderStore;

    public function __construct(AdminMenuBuilderStore $adminMenuBuilderStore)
    {
        $this->adminMenuBuilderStore = $adminMenuBuilderStore;
    }

    public function getAdminMenuItems(): array
    {
        return $this->adminMenuBuilderStore->getAdminMenuBuilder()->getMenuItems();
    }
}
