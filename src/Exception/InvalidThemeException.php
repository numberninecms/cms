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
use NumberNine\Model\Theme\ThemeInterface;

/**
 * Class InvalidThemeException
 * @package NumberNine\Exception
 */
final class InvalidThemeException extends Exception
{
    /**
     * InvalidThemeException constructor.
     * @param ThemeInterface $theme
     */
    public function __construct(ThemeInterface $theme)
    {
        $class = get_class($theme);
        parent::__construct("$class is an invalid theme. Check getName() is set correctly and returns a valid name.");
    }
}
