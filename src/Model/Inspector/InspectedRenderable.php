<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Inspector;

use NumberNine\Annotation\Shortcode\Exclude;
use NumberNine\Annotation\Shortcode\ExclusionPolicy;
use NumberNine\Annotation\Shortcode\Expose;
use NumberNine\Model\Component\RenderableInterface;
use ReflectionClass;

final class InspectedRenderable
{
    private RenderableInterface $renderable;
    private ReflectionClass $reflection;
    private string $exclusionPolicy;
    private array $excludes;
    private array $exposes;
    private bool $isSerialization;

    public function __construct(RenderableInterface $renderable, string $exclusionPolicy, array $excludes, array $exposes, bool $isSerialization)
    {
        $this->renderable = $renderable;
        $this->reflection = new ReflectionClass($renderable);
        $this->exclusionPolicy = $exclusionPolicy;
        $this->excludes = $excludes;
        $this->exposes = $exposes;
        $this->isSerialization = $isSerialization;
    }

    public function getExposedValues(): array
    {
        $values = [];

        foreach ($this->getExposedGetters() as $getter) {
            $values[lcfirst(substr($getter, 3))] = $this->renderable->$getter();
        }

        foreach ($this->getExposedProperties() as $property) {
            $values[$property] = $this->renderable->$property;
        }

        return $values;
    }

    public function getExposedGetters(): array
    {
        $exposedGetters = [];

        foreach ($this->reflection->getMethods() as $method) {
            if (!$method->isPublic() || !$this->isGetter($method->getName())) {
                continue;
            }

            $relatedProperty = $this->getRelatedProperty($method->getName());

            if (
                (!$relatedProperty && !$this->isExcluded($method->getName()))
                || ($relatedProperty && !$this->isExcluded($relatedProperty) && !$this->isExcluded($method->getName()))
            ) {
                $exposedGetters[] = $method->getName();
            }
        }

        return $exposedGetters;
    }

    public function getExposedProperties(): array
    {
        $exposedProperties = [];

        foreach ($this->reflection->getProperties() as $property) {
            if (!$property->isPublic()) {
                continue;
            }

            if (!$this->isExcluded($property->getName())) {
                $exposedProperties[] = $property->getName();
            }
        }

        return $exposedProperties;
    }

    public function getDefaultProperties(ReflectionClass $class = null): array
    {
        if (!$class) {
            $class = $this->reflection;
        }

        $values = $class->getDefaultProperties();

        if ($parent = $class->getParentClass()) {
            $values = array_merge($this->getDefaultProperties($parent), $values);
        }

        return $values;
    }

    private function isExcluded(string $propertyOrMethod): bool
    {
        if ($this->exclusionPolicy === ExclusionPolicy::NONE) {
            if (array_key_exists($propertyOrMethod, $this->excludes)) {
                return $this->excludes[$propertyOrMethod]->value === Exclude::ALL
                    || ($this->excludes[$propertyOrMethod]->value === Exclude::VIEW && !$this->isSerialization)
                    || ($this->excludes[$propertyOrMethod]->value === Exclude::SERIALIZATION && $this->isSerialization);
            }

            return false;
        }

        if (array_key_exists($propertyOrMethod, $this->exposes)) {
            return !($this->exposes[$propertyOrMethod]->value === Expose::ALL
                || ($this->exposes[$propertyOrMethod]->value === Expose::VIEW && !$this->isSerialization)
                || ($this->exposes[$propertyOrMethod]->value === Expose::SERIALIZATION && $this->isSerialization));
        }

        return true;
    }

    private function isGetter(string $method): bool
    {
        return strpos($method, 'get') === 0
            && strlen($method) > 3
            && $method[3] !== strtolower($method[3]);
    }

    private function getRelatedProperty(string $getter): ?string
    {
        $relatedProperty = lcfirst(substr($getter, 3));

        return $this->reflection->hasProperty($relatedProperty) ? $relatedProperty : null;
    }
}
