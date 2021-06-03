<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\Exception;

final class ClassNotFoundException extends \LogicException
{
    public function __construct(string $className)
    {
        parent::__construct(sprintf('Class "%s" does not exist.', $className));
    }
}