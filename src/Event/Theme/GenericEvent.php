<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Event\Theme;

abstract class GenericEvent extends AbstractThemeEvent implements \Stringable
{
    /**
     * GenericEvent constructor.
     * @param mixed $object
     */
    public function __construct(protected $object = null)
    {
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param mixed $object
     */
    public function setObject($object): void
    {
        $this->object = $object;
    }

    public function __toString(): string
    {
        return (string)$this->getObject();
    }
}
