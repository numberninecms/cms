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

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class AdminExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('N9_admin_menu_items', [AdminRuntime::class, 'getAdminMenuItems']),
        ];
    }
}
