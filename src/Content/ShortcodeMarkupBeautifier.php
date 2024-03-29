<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\Content;

final class ShortcodeMarkupBeautifier
{
    public function __construct(private ShortcodeProcessor $shortcodeProcessor, private ArrayToShortcodeConverter $arrayToShortcodeConverter)
    {
    }

    public function beautify(string $text): string
    {
        $tree = $this->shortcodeProcessor->buildShortcodeTree($text);

        return $this->arrayToShortcodeConverter->convertMany($tree, true);
    }
}
