<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Twig\Extension;

use Twig\Extension\RuntimeExtensionInterface;

final class ColorRuntime implements RuntimeExtensionInterface
{
    public function hexToRgb(string $hex): string
    {
        [$red, $green, $blue] = sscanf($hex, "#%02x%02x%02x");

        return "$red, $green, $blue";
    }
}
