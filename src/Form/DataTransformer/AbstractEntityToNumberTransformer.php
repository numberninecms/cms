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

use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

abstract class AbstractEntityToNumberTransformer implements DataTransformerInterface
{
    private EntityManagerInterface $entityManager;
    private string $className;

    public function __construct(EntityManagerInterface $entityManager, string $className)
    {
        $this->entityManager = $entityManager;
        $this->className = $className;
    }

    /**
     * @param mixed $value
     * @return int|string
     */
    public function transform($value)
    {
        if ($value === null || !is_object($value)) {
            return '';
        }

        if (!method_exists($value, 'getId')) {
            throw new InvalidArgumentException(sprintf("Method getId() doesn't exist on class %s", get_class($value)));
        }

        return $value->getId();
    }

    /**
     * @param mixed $value
     * @return mixed|null
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        $entity = $this->entityManager->getRepository($this->className)->find($value);

        if ($entity === null) {
            throw new TransformationFailedException(sprintf('An entity with ID "%s" does not exist!', $value));
        }

        return $entity;
    }
}
