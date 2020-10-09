<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Util\StringUtil;

/**
 * Converts a human readable size to number in byte.
 *
 * Example: human_readable_size_to_int('2K') = 2048
 */
function human_readable_size_to_int(string $size): int
{
    $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
    $size = preg_replace('/[^0-9.]/', '', $size);

    if ($unit) {
        return (int)round((float)$size * (1024 ** (int)stripos('bkmgtpezy', $unit[0])));
    }

    return (int)round((float)$size);
}
