<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Theme\CssFramework;

use function NumberNine\Util\ArrayUtil\in_array_all;

final class TailwindCss implements CssFrameworkInterface
{
    public function getResponsiveVisibilityClasses(array $visibleViewSizes): array
    {
        $isMobileFirstVisible = in_array('sm', $visibleViewSizes, true);
        $hiddenViewSizes = array_diff(['lg', 'md', 'sm'], $visibleViewSizes);
        $classes = [];

        if ($isMobileFirstVisible) {
            if (in_array_all(['md', 'lg'], $hiddenViewSizes)) {
                $classes[] = 'md:hidden';
            } elseif (in_array('md', $hiddenViewSizes, true)) {
                $classes[] = 'md:hidden lg:block';
            } elseif (in_array('lg', $hiddenViewSizes, true)) {
                $classes[] = 'lg:hidden';
            }

            return $classes;
        }

        $classes[] = 'hidden';

        if (in_array_all(['md', 'lg'], $visibleViewSizes)) {
            $classes[] = 'md:block';
        } elseif (in_array('md', $visibleViewSizes, true)) {
            $classes[] = 'md:block lg:hidden';
        } elseif (in_array('lg', $visibleViewSizes, true)) {
            $classes[] = 'lg:block';
        }

        return $classes;
    }
}
