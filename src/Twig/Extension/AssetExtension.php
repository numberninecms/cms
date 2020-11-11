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

final class AssetExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'N9_theme_stylesheet',
                [AssetRuntime::class, 'renderStylesheetTag'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction('N9_theme_script', [AssetRuntime::class, 'renderScriptTag'], ['is_safe' => ['html']]),
            new TwigFunction('N9_theme_assets', [AssetRuntime::class, 'renderEntryTags'], ['is_safe' => ['html']]),
        ];
    }
}
