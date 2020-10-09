<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ThemeExtension extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('current_theme', [ThemeRuntime::class, 'getCurrentTheme']),
            new TwigFunction('N9_area', [ThemeRuntime::class, 'renderArea'], ['is_safe' => ['html']]),
            new TwigFunction('N9_component', [ThemeRuntime::class, 'renderComponent'], ['is_safe' => ['html']]),
            new TwigFunction('N9_theme_option', [ThemeRuntime::class, 'getThemeOption']),
            new TwigFunction('N9_setting', [ThemeRuntime::class, 'getSetting']),
            new TwigFunction('N9_entity_url', [ThemeRuntime::class, 'getEntityUrl']),
            new TwigFunction('N9_entity_link', [ThemeRuntime::class, 'getEntitylink'], ['is_safe' => ['html']]),
            new TwigFunction('N9_entity_admin_url', [ThemeRuntime::class, 'getEntityAdminUrl'], ['is_safe' => ['html']]),
            new TwigFunction('N9_terms_links', [ThemeRuntime::class, 'getTermsLinkList'], ['is_safe' => ['html']]),
            new TwigFunction('N9_term_link', [ThemeRuntime::class, 'getTermLink'], ['is_safe' => ['html']]),
            new TwigFunction('N9_base_template', [ThemeRuntime::class, 'getBaseTemplate']),
            new TwigFunction('N9_path', [ThemeRuntime::class, 'getPath']),
            new TwigFunction('N9_page', [ThemeRuntime::class, 'getCurrentRoutePagePath']),
            new TwigFunction('N9_is_home', [ThemeRuntime::class, 'isHomepage']),
        ];
    }
}
