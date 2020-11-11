<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Theme;

use NumberNine\Model\Shortcode\ShortcodeInterface;

interface PresetFinderInterface
{
    public function findShortcodePresets(ShortcodeInterface $shortcode): array;
}
