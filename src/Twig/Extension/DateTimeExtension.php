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

final class DateTimeExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('time_left', [DateTimeRuntime::class, 'getTimeLeftInWords']),
            new TwigFunction('time_ago', [DateTimeRuntime::class, 'getTimeAgoInWords']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('time_left', [DateTimeRuntime::class, 'getTimeLeftInWords']),
            new TwigFilter('time_ago', [DateTimeRuntime::class, 'getTimeAgoInWords']),
        ];
    }
}
