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

use Exception;

/**
 * Class InvalidCustomizerControlTypeException
 * @package NumberNine\Exception
 */
final class InvalidCustomizerControlTypeException extends Exception
{
    /**
     * InvalidCustomizerControlTypeException constructor.
     */
    public function __construct(string $type)
    {
        parent::__construct(sprintf('"%s" is not a valid control type. Allowed types are "setting" and "option".', $type));
    }
}
