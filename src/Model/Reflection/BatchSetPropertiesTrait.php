<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Reflection;

use LogicException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;

use function Symfony\Component\String\u;

trait BatchSetPropertiesTrait
{
    use ResetOwnPropertiesTrait;

    private function batchSetProperties(array $values, bool $isSerialization = false): void
    {
        try {
            $this->reset($isSerialization);
            $reflection = new ReflectionClass($this);
        } catch (ReflectionException $e) {
            throw new LogicException($e->getMessage());
        }

        foreach ($values as $field => $value) {
            $setter = 'set' . u($field)->camel()->title();
            $property = u($field)->camel();

            try {
                if (method_exists($this, $setter)) {
                    $reflectionMethod = new ReflectionMethod($this, $setter);
                    if (($parameters = $reflectionMethod->getParameters()) && count($parameters) > 0) {
                        if ($type = $parameters[0]->getType()) {
                            if ($field !== 'content') {
                                $value = $this->prepareValue($value);
                            }

                            /** @var ReflectionNamedType $type */
                            settype($value, $type->getName());
                        }
                        $this->$setter($value);
                    }
                } elseif (property_exists($this, $property) && $reflection->getProperty($property)->isPublic()) {
                    $this->$property = $value;
                } elseif (method_exists($this, 'addUnknownParameter')) {
                    $this->addUnknownParameter($field, $value);
                }
            } catch (ReflectionException $e) {
                throw new LogicException($e->getMessage());
            }
        }
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function prepareValue($value)
    {
        if (is_string($value) && strpos($value, '|') !== false) {
            $value = explode('|', $value);
        }

        return $value;
    }
}
