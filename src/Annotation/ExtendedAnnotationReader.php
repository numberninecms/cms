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
use NumberNine\Exception\AnnotationOrAttributeMissingException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

final class ExtendedAnnotationReader implements ExtendedReader
{
    public function __construct(private Reader $decoratedAnnotationReader)
    {
    }

    /**
     * @throws ReflectionException
     */
    public function getAllAnnotationsAndAttributes(object|string $object): array
    {
        $annotations = [];

        $reflection = new ReflectionClass($object);

        if ($parent = $reflection->getParentClass()) {
            $annotations = array_merge($annotations, $this->getAllAnnotationsAndAttributes($parent->getName()));
        }

        $classAnnotations = $this->decoratedAnnotationReader->getClassAnnotations($reflection);

        if (!empty($classAnnotations)) {
            $annotations[$reflection->getName()] = $classAnnotations;
        }

        $classAttributes = $reflection->getAttributes();

        if (!empty($classAttributes)) {
            $annotations[$reflection->getName()] = array_merge(
                $annotations[$reflection->getName()] ?? [],
                array_map(static function (ReflectionAttribute $attribute): object {
                    return $attribute->newInstance();
                }, $classAttributes),
            );
        }

        foreach ($reflection->getProperties() as $property) {
            $propertyAnnotations = $this->decoratedAnnotationReader->getPropertyAnnotations($property);

            if (!empty($propertyAnnotations)) {
                $annotations[$property->getName()] = $propertyAnnotations;
            }

            $propertyAttributes = $property->getAttributes();

            if (!empty($propertyAttributes)) {
                $annotations[$property->getName()] = array_merge(
                    $annotations[$property->getName()] ?? [],
                    array_map(static function (ReflectionAttribute $attribute): object {
                        return $attribute->newInstance();
                    }, $propertyAttributes),
                );
            }
        }

        foreach ($reflection->getMethods() as $method) {
            $methodAnnotations = $this->decoratedAnnotationReader->getMethodAnnotations($method);

            if (!empty($methodAnnotations)) {
                $annotations[$method->getName()] = $methodAnnotations;
            }

            $methodAttributes = $method->getAttributes();

            if (!empty($methodAttributes)) {
                $annotations[$method->getName()] = array_merge(
                    $annotations[$method->getName()] ?? [],
                    array_map(static function (ReflectionAttribute $attribute): object {
                        return $attribute->newInstance();
                    }, $methodAttributes),
                );
            }
        }

        return $annotations;
    }

    /**
     * @throws ReflectionException
     */
    public function getAnnotationsOrAttributesOfType(object|array|string $object, string $type): array
    {
        $groups = \is_array($object) ? $object : $this->getAllAnnotationsAndAttributes($object);
        $annotationsOfType = [];

        foreach ($groups as $target => $annotations) {
            foreach ($annotations as $annotation) {
                if ($annotation instanceof $type) {
                    $annotationsOfType[$target] = $annotation;
                }
            }
        }

        return $annotationsOfType;
    }

    /**
     * @throws ReflectionException
     */
    public function getFirstAnnotationOrAttributeOfType(
        object|array|string $object,
        string $type,
        bool $throwException = false,
    ): mixed {
        $annotationsOfType = $this->getAnnotationsOrAttributesOfType($object, $type);

        if (empty($annotationsOfType)) {
            if ($throwException) {
                if (\is_array($object)) {
                    throw new AnnotationOrAttributeMissingException($type);
                }

                throw new AnnotationOrAttributeMissingException(
                    $type,
                    \is_string($object) ? $object : $object::class,
                );
            }

            return null;
        }

        return current($annotationsOfType);
    }

    /**
     * @throws ReflectionException
     */
    public function getValueOfFirstAnnotationOrAttributeOfType(
        object|array|string $object,
        string $type,
        mixed $default = null,
        string $property = 'value',
    ): mixed {
        $annotation = $this->getFirstAnnotationOrAttributeOfType($object, $type);

        if ($annotation && property_exists($type, $property)) {
            return $annotation->{$property};
        }

        return $default;
    }

    public function getClassAnnotations(ReflectionClass $class): array
    {
        return $this->decoratedAnnotationReader->getClassAnnotations($class);
    }

    public function getClassAnnotation(ReflectionClass $class, $annotationName): ?object
    {
        return $this->decoratedAnnotationReader->getClassAnnotation($class, $annotationName);
    }

    public function getMethodAnnotations(ReflectionMethod $method): array
    {
        return $this->decoratedAnnotationReader->getMethodAnnotations($method);
    }

    public function getMethodAnnotation(ReflectionMethod $method, $annotationName): ?object
    {
        return $this->decoratedAnnotationReader->getMethodAnnotation($method, $annotationName);
    }

    public function getPropertyAnnotations(ReflectionProperty $property): array
    {
        return $this->decoratedAnnotationReader->getPropertyAnnotations($property);
    }

    public function getPropertyAnnotation(ReflectionProperty $property, $annotationName): ?object
    {
        return $this->decoratedAnnotationReader->getPropertyAnnotation($property, $annotationName);
    }
}
