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
    public function convertMany(array $arrays): string
    {
        $text = '';

        foreach ($arrays as $array) {
            $text .= $this->convert($array);
        }

        return trim($this->purifyOutput($text));
    }

    public function convert(array $array): string
    {
        $text = sprintf('[%s', $array['name']);

        $parameters = array_filter($array['parameters'], fn($v, $k) => $v && $k !== 'content', ARRAY_FILTER_USE_BOTH);

        if (!empty($parameters)) {
            $text .= ' ' . array_implode_associative($parameters, ' ', '=', '', '"');
        }

        if (!empty($array['children'])) {
            $text .= sprintf("]%s[/%s]\n", $this->convertMany($array['children']), $array['name']);
        } elseif (!empty($array['parameters']['content'])) {
            $text .= sprintf("]%s[/%s]\n", $array['parameters']['content'], $array['name']);
        } else {
            $text .= "/]\n";
        }

        return $text;
    }

    private function purifyOutput(string $text): string
    {
        $text = str_replace(['[text]', '[/text]'], ["\n", "\n\n"], $text);

        return (string)preg_replace('/\n\n+/m', "\n\n", $text);
    }
}
