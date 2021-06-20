<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Content;

use function NumberNine\Common\Util\ArrayUtil\array_implode_associative;

final class ArrayToShortcodeConverter
{
    public function convertMany(array $arrays, bool $beautify = false, int $indentationLevel = 0): string
    {
        $text = '';

        foreach ($arrays as $array) {
            $text .= $this->convert($array, $beautify, $indentationLevel);
        }

        return trim($this->purifyOutput($text), "\n");
    }

    public function convert(array $array, bool $beautify = false, int $indentationLevel = 0): string
    {
        $indent = $beautify ? str_repeat(' ', $indentationLevel * 4) : '';
        $nextIndent = $beautify ? str_repeat(' ', ($indentationLevel + 1) * 4) : '';
        $text = sprintf('%s[%s', "\n$indent", $array['name']);

        $parameters = array_filter(
            $array['parameters'],
            static fn($v, $k) => $v && $k !== 'content',
            ARRAY_FILTER_USE_BOTH,
        );

        if (!empty($parameters)) {
            $text .= ' ' . array_implode_associative($parameters, ' ', '=', '', '"');
        }

        if (!empty($array['children'])) {
            $text .= sprintf(
                ']%s%s%s[/%s]',
                $beautify ? "\n" : '',
                $this->convertMany($array['children'], $beautify, $indentationLevel + 1),
                $beautify ? "\n$indent" : '',
                $array['name'],
            );
        } elseif (!empty($array['parameters']['content'])) {
            $text .= sprintf(
                ']%s%s%s[/%s]',
                $beautify ? "\n$nextIndent" : '',
                $array['parameters']['content'],
                $beautify ? "\n$indent" : '',
                $array['name'],
            );
        } else {
            $text .= '/]' . ($beautify ? "\n" : '');
        }

        return $text;
    }

    public function purifyOutput(string $text): string
    {
        $text = (string)preg_replace_callback('@\[text](.*)\[/text]@simU', static fn ($m) => trim($m[1]), $text);

        return (string)preg_replace('/\n\n+/m', "\n\n", $text);
    }
}
