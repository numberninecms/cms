<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\General;

use InvalidArgumentException;
use NumberNine\Annotation\ExtendedReader;

abstract class AbstractMetadataFactory
{
    protected ExtendedReader $annotationReader;

    /** @var Descriptor[] */
    private array $loadedMetadata = [];

    public function __construct(ExtendedReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function getMetadataFor(object $object, string $annotationClassName, string $metadataClassName): Descriptor
    {
        $className = get_class($object);

        if (isset($this->loadedMetadata[$className])) {
            return $this->loadedMetadata[$className];
        }

        if (!is_subclass_of($metadataClassName, Descriptor::class)) {
            throw new InvalidArgumentException();
        }

        $metadata = $this->annotationReader->getFirstAnnotationOfType($object, $annotationClassName, true);
        $this->loadedMetadata[$className] = new $metadataClassName($metadata);

        return $this->loadedMetadata[$className];
    }
}
