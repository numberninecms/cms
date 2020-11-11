<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Shortcode;

interface ResponsiveShortcodeInterface
{
    public function getVisibility(): array;

    public function setVisibility(array $visibility = []): void;

    public function getVisibleViewSizes(): array;

    public function getHiddenViewSizes(): array;
}
