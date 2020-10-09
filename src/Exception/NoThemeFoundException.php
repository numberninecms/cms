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

final class NoThemeFoundException extends LogicException
{
    public function __construct()
    {
        parent::__construct('You must install a theme in order to use NumberNine.');
    }
}
