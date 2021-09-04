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
    /**
     * @param object|string $object
     */
    public function getAllAnnotations($object): array;

    /**
     * @param object|array|string $object
     */
    public function getAnnotationsOfType($object, string $type): array;

    /**
     * @param object|array|string $object
     * @return mixed|null
     */
    public function getFirstAnnotationOfType($object, string $type, bool $throwException = false);

    /**
     * @param object|array|string $object
     * @param mixed $default
     * @return mixed|null
     */
    public function getValueOfFirstAnnotationOfType($object, string $type, $default = null, string $property = 'value');
}
