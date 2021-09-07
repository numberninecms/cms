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

use RuntimeException;

/**
 * Class ThemeNotFoundException.
 */
final class ThemeNotFoundException extends RuntimeException
{
    /**
     * ThemeAlreadyExistsException constructor.
     */
    public function __construct(string $themeName)
    {
        parent::__construct("Theme with name \"{$themeName}\" not found.");
    }
}
