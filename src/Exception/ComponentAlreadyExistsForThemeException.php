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
use NumberNine\Model\Component\ComponentInterface;
use NumberNine\Model\Theme\ThemeInterface;

final class ComponentAlreadyExistsForThemeException extends Exception
{
    /**
     * ComponentAlreadyExistsForThemeException constructor.
     * @param ThemeInterface $theme
     * @param ComponentInterface $component
     */
    public function __construct(ThemeInterface $theme, ComponentInterface $component)
    {
        parent::__construct(sprintf(
            'Component "%s" is already registered for theme "%s" which is theoretically impossible.',
            get_class($component),
            $theme->getName()
        ));
    }
}
