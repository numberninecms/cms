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
use Symfony\Component\Serializer\SerializerInterface;

final class SerializerTransformer implements DataTransformerInterface
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    /**
     * @param array $value
     */
    public function transform($value): string
    {
        return $this->serializer->serialize($value, 'json');
    }

    /**
     * @param string $value
     */
    public function reverseTransform($value): array
    {
        return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
    }
}
