<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

final class AssociativeArrayToKeyValueCollectionTransformer implements DataTransformerInterface
{
    /**
     * @param array $value
     */
    public function transform($value): array
    {
        return $value;
    }

    /**
     * @param array $value
     */
    public function reverseTransform($value): array
    {
        if (!$value) {
            return [];
        }

        $output = [];

        foreach ($value as $keyValuePair) {
            $output[$keyValuePair['key']] = $keyValuePair['value'];
        }

        return $output;
    }
}
