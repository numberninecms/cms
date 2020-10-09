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
 * Class CustomizerControlNotFoundException
 * @package NumberNine\Exception
 */
final class CustomizerControlNotFoundException extends Exception
{
    /**
     * CustomizerControlNotFoundException constructor.
     * @param string $controlType
     */
    public function __construct(string $controlType)
    {
        parent::__construct(sprintf('Control with class type "%s" was not found. Register it prior to using it.', $controlType));
    }
}
