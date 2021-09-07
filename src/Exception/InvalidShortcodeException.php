<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Exception;

use LogicException;

/**
 * Class InvalidShortcodeException.
 */
final class InvalidShortcodeException extends LogicException
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('"%s" refers to a shortcode that doesn\'t exist.', $name));
    }
}
