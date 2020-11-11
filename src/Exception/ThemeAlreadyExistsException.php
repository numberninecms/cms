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
 * Class ThemeAlreadyExistsException
 * @package NumberNine\Exception
 */
final class ThemeAlreadyExistsException extends Exception
{
    /**
     * ThemeAlreadyExistsException constructor.
     * @param ThemeInterface $theme
     */
    public function __construct(ThemeInterface $theme)
    {
        $class = get_class($theme);
        parent::__construct(sprintf(
            'Theme with name "%s" already exists, please choose a different name for "%s".',
            $theme->getName(),
            $class
        ));
    }
}
