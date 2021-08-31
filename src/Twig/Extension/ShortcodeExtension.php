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

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ShortcodeExtension extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('N9_shortcode', [ShortcodeRuntime::class, 'renderShortcode'], ['is_safe' => ['html']])
        ];
    }
}
