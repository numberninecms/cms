<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Component\Features;

trait OptionsAndSettingsInjectionTrait
{
    /**
     * @inheritDoc
     */
    public static function injectThemeOptions(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public static function injectSettings(): array
    {
        return [];
    }
}
