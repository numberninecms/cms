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
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;
use RuntimeException;

final class ExtendedAnnotationReader implements ExtendedReader
{
    public function __construct(private Reader $decoratedAnnotationReader)
    {
    }

    /**
     * @param object|string $object
     * @throws ReflectionException
     */
    public function getAllAnnotations($object): array
    {
        $annotations = [];

        $reflection = new ReflectionClass($object);

        if ($parent = $reflection->getParentClass()) {
            $annotations = array_merge($annotations, $this->getAllAnnotations($parent->getName()));
        }

        $classAnnotations = $this->decoratedAnnotationReader->getClassAnnotations($reflection);

        if (!empty($classAnnotations)) {
            $annotations[$reflection->getName()] = $classAnnotations;
        }

        foreach ($reflection->getProperties() as $property) {
            $propertyAnnotations = $this->decoratedAnnotationReader->getPropertyAnnotations($property);

            if (!empty($propertyAnnotations)) {
                $annotations[$property->getName()] = $propertyAnnotations;
            }
        }

        foreach ($reflection->getMethods() as $method) {
            $methodAnnotations = $this->decoratedAnnotationReader->getMethodAnnotations($method);

            if (!empty($methodAnnotations)) {
                $annotations[$method->getName()] = $methodAnnotations;
            }
        }

        return $annotations;
    }

    /**
     * @param object|array|string $object
     * @throws ReflectionException
     */
    public function getAnnotationsOfType($object, string $type): array
    {
        $groups = is_array($object) ? $object : $this->getAllAnnotations($object);
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
     * @param object|array|string $object
     * @return mixed|null
     * @throws ReflectionException
     */
    public function getFirstAnnotationOfType($object, string $type, bool $throwException = false)
    {
        $annotationsOfType = $this->getAnnotationsOfType($object, $type);

        if (empty($annotationsOfType)) {
            if ($throwException) {
                if (is_array($object)) {
                    throw new RuntimeException(sprintf('Annotation of type "%s" is missing.', $type));
                }

                throw new RuntimeException(sprintf(
                    'Annotation of type "%s" is missing on "%s" class.',
                    $type,
                    is_string($object) ? $object : $object::class
                ));
            }

            return null;
        }

        return current($annotationsOfType);
    }

    /**
     * @param object|array|string $object
     * @param mixed $default
     * @return mixed|null
     * @throws ReflectionException
     */
    public function getValueOfFirstAnnotationOfType($object, string $type, $default = null, string $property = 'value')
    {
        $annotation = $this->getFirstAnnotationOfType($object, $type);

        if ($annotation && property_exists($type, $property)) {
            return $annotation->$property;
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
