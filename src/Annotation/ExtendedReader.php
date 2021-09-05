<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Annotation;

use Doctrine\Common\Annotations\Reader;

interface ExtendedReader extends Reader
{
    public function getAllAnnotationsAndAttributes(object|string $object): array;

    public function getAnnotationsOrAttributesOfType(object|array|string $object, string $type): array;

    public function getFirstAnnotationOrAttributeOfType(
        object|array|string $object,
        string $type,
        bool $throwException = false,
    ): mixed;

    public function getValueOfFirstAnnotationOrAttributeOfType(
        object|array|string $object,
        string $type,
        mixed $default = null,
        string $property = 'value',
    ): mixed;
}
