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
use Twig\TwigFilter;
use Twig\TwigFunction;

final class EventExtension extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            // Sections
            new TwigFunction('N9_head', [EventRuntime::class, 'head'], ['is_safe' => ['html']]),
            new TwigFunction('N9_footer', [EventRuntime::class, 'footer'], ['is_safe' => ['html']]),

            // Hook system
            new TwigFunction('N9_dispatch', [EventRuntime::class, 'dispatch'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [new TwigFilter('N9_filter', [EventRuntime::class, 'filter'])];
    }
}
