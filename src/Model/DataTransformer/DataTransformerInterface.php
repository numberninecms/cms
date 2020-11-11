<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\DataTransformer;

interface DataTransformerInterface
{
    /**
     * @param mixed $object
     * @return bool
     */
    public function supports($object): bool;

    /**
     * @param mixed $object
     * @return mixed
     */
    public function transform($object);
}
