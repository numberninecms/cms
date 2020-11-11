<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Component;

interface OptionsAndSettingsInjectableInterface
{
    /**
     * Returns an array of theme options to inject into the template as variables.
     *
     * For instance:
     *
     *  * ['theme_option_name_1', 'theme_option_name_2']
     *  * ['my_custom_template_variable_name' => 'theme_option_name']
     *
     * @return array The theme options to inject into the template
     */
    public static function injectThemeOptions(): array;

    /**
     * Returns an array of settings to inject into the template as variables.
     *
     * For instance:
     *
     *  * ['setting_name_1', 'setting_name_2']
     *  * ['my_custom_template_variable_name' => 'setting_name']
     *
     * @return array The settings to inject into the template
     */
    public static function injectSettings(): array;
}
