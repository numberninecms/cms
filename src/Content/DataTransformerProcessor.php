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

use NumberNine\Model\DataTransformer\DataTransformerInterface;
use ReflectionClass;
use ReflectionException;

final class DataTransformerProcessor
{
    public function __construct(private iterable $dataTransformers)
    {
    }

    /**
     * @param mixed $object
     * @return mixed
     * @throws ReflectionException
     */
    public function transform($object)
    {
        if (is_object($object)) {
            $reflection = new ReflectionClass($object);
            $data = $reflection->isCloneable() ? clone $object : $object;
        } else {
            $data = $object;
        }

        foreach ($this->dataTransformers as $dataTransformer) {
            if ($dataTransformer->supports($data)) {
                $data = $dataTransformer->transform($data);
            }
        }

        return $data;
    }
}
