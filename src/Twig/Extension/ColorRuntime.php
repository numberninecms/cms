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

use NumberNine\Exception\InvalidHexColorValueException;
use Twig\Extension\RuntimeExtensionInterface;

final class ColorRuntime implements RuntimeExtensionInterface
{
    public function hexToRgb(string $hex): string
    {
        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $hex)) {
            throw new InvalidHexColorValueException($hex);
        }

        [$red, $green, $blue] = sscanf($hex, '#%02x%02x%02x');

        return "{$red}, {$green}, {$blue}";
    }
}
