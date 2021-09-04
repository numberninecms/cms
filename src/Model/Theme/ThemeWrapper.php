<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Theme;

final class ThemeWrapper
{
    public function __construct(private ThemeDescriptor $descriptor, private ThemeInterface $theme)
    {
    }

    public function getDescriptor(): ThemeDescriptor
    {
        return $this->descriptor;
    }

    public function getTheme(): ThemeInterface
    {
        return $this->theme;
    }
}
