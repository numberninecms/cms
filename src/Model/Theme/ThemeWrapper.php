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
    private ThemeDescriptor $descriptor;
    private ThemeInterface $theme;

    public function __construct(ThemeDescriptor $descriptor, ThemeInterface $theme)
    {
        $this->descriptor = $descriptor;
        $this->theme = $theme;
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
