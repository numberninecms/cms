<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Theme;

use NumberNine\Exception\ThemeNotFoundException;
use NumberNine\Model\General\Settings;
use NumberNine\Configuration\ConfigurationReadWriter;
use NumberNine\Http\RequestAnalyzer;
use Psr\Cache\CacheException;
use Psr\Cache\InvalidArgumentException;

final class ThemeToolbox
{
    private ThemeStore $themeStore;
    private ThemeOptionsReadWriter $themeOptionsReadWriter;
    private ConfigurationReadWriter $configurationReadWriter;
    private RequestAnalyzer $requestAnalyzer;

    public function __construct(
        ThemeStore $themeStore,
        ThemeOptionsReadWriter $themeOptionsReadWriter,
        ConfigurationReadWriter $configurationReadWriter,
        RequestAnalyzer $requestAnalyzer
    ) {
        $this->themeStore = $themeStore;
        $this->themeOptionsReadWriter = $themeOptionsReadWriter;
        $this->configurationReadWriter = $configurationReadWriter;
        $this->requestAnalyzer = $requestAnalyzer;
    }

    /**
     * @param string $optionName
     * @return mixed
     */
    public function getThemeOption(string $optionName)
    {
        try {
            return $this->themeOptionsReadWriter->read(
                $this->themeStore->getCurrentTheme(),
                $optionName,
                null,
                false,
                $this->requestAnalyzer->isPreviewMode()
            );
        } catch (ThemeNotFoundException $e) {
            return null;
        }
    }

    /**
     * @param string $settingName
     * @return mixed
     * @throws CacheException
     * @throws InvalidArgumentException
     */
    public function getSetting(string $settingName)
    {
        $settings = [$settingName => $this->configurationReadWriter->read($settingName)];

        if ($this->requestAnalyzer->isPreviewMode()) {
            $settings = array_merge(
                $settings,
                $this->configurationReadWriter->read(Settings::CUSTOMIZER_DRAFT_SETTINGS, [])
            );
        }

        return $settings[$settingName] ?? null;
    }
}
