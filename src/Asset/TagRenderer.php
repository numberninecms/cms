<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Asset;

use Exception;
use NumberNine\Model\Theme\ThemeInterface;
use NumberNine\Theme\ThemeStore;
use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\WebLink\GenericLinkProvider;
use Symfony\Component\WebLink\Link;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupCollection;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupInterface;

final class TagRenderer
{
    private ?Request $request;
    private EntrypointLookupCollection $entrypointLookupCollection;

    public function __construct(
        private ThemeStore $themeStore,
        private Packages $packages,
        RequestStack $requestStack,
        EntrypointLookupInterface|EntrypointLookupCollection $entrypointLookupCollection
    ) {
        if ($entrypointLookupCollection instanceof EntrypointLookupInterface) {
            @trigger_error(
                sprintf(
                    'The "$entrypointLookupCollection" argument in method "%s()" must be an instance of ' .
                    'EntrypointLookupCollection.',
                    __METHOD__
                ),
                E_USER_DEPRECATED
            );

            $this->entrypointLookupCollection = new EntrypointLookupCollection(
                new ServiceLocator(
                    [
                        '_default' => function () use ($entrypointLookupCollection): EntrypointLookupInterface {
                            return $entrypointLookupCollection;
                        },
                    ]
                )
            );
        } else {
            $this->entrypointLookupCollection = $entrypointLookupCollection;
        }

        $this->request = $requestStack->getMainRequest();
    }

    public function renderWebpackScriptTags(
        string $entryName = null,
        string $configName = null,
        bool $ignoreRuntime = false
    ): string {
        $entryName = $entryName ?? $this->getThemeMainEntry($this->themeStore->getCurrentTheme());
        $configName = $configName ?? $this->themeStore->getCurrentTheme()->getWebpackConfigName();

        $scriptTags = [];

        try {
            foreach ($this->getEntrypointLookup($configName)->getJavaScriptFiles((string) $entryName) as $filename) {
                if ($ignoreRuntime && preg_match('@(:?/runtime.*\.js)$@iU', $filename)) {
                    continue;
                }

                $assetPath = $this->getAssetPath($filename, $configName);

                if ($this->request) {
                    $linkProvider = $this->request->attributes->get('_links', new GenericLinkProvider());
                    $this->request->attributes->set(
                        '_links',
                        $linkProvider->withLink((new Link('preload', $assetPath))->withAttribute('as', 'script'))
                    );
                }

                $scriptTags[] = sprintf('<script src="%s" defer></script>', $assetPath);
            }
        } catch (\InvalidArgumentException $e) {
            if ($configName !== 'app') {
                throw $e;
            }
        }

        return implode('', $scriptTags);
    }

    /**
     * @throws Exception
     */
    public function renderWebpackLinkTags(string $entryName = null, string $configName = null): string
    {
        $assetPaths = $this->getWebpackLinkStylesheetsPaths($entryName, $configName);
        $scriptTags = [];

        foreach ($assetPaths as $assetPath) {
            if ($this->request) {
                $linkProvider = $this->request->attributes->get('_links', new GenericLinkProvider());
                $this->request->attributes->set('_links', $linkProvider->withLink(
                    (new Link('preload', $assetPath))->withAttribute('as', 'style')
                ));
            }

            $scriptTags[] = sprintf('<link rel="stylesheet" href="%s">', $assetPath);
        }

        return implode('', $scriptTags);
    }

    /**
     * @throws Exception
     *
     * @return string[]
     */
    public function getWebpackLinkStylesheetsPaths(string $entryName = null, string $configName = null): array
    {
        $entryName = $entryName ?? $this->getThemeMainEntry($this->themeStore->getCurrentTheme());
        $configName = $configName ?? $this->themeStore->getCurrentTheme()->getWebpackConfigName();

        $assetPaths = [];

        try {
            foreach ($this->getEntrypointLookup($configName)->getCssFiles((string) $entryName) as $filename) {
                $assetPaths[] = $this->getAssetPath($filename, $configName);
            }
        } catch (\InvalidArgumentException $e) {
            if ($configName !== 'app') {
                throw $e;
            }
        }

        return $assetPaths;
    }

    private function getThemeMainEntry(ThemeInterface $theme): ?string
    {
        return $theme->getConfiguration()['main_entry'] ?? null;
    }

    private function getAssetPath(string $assetPath, string $packageName = null): string
    {
        return $this->packages->getUrl($assetPath, $packageName === 'app' ? null : $packageName);
    }

    private function getEntrypointLookup(string $buildName): EntrypointLookupInterface
    {
        return $this->entrypointLookupCollection->getEntrypointLookup($buildName === 'app' ? null : $buildName);
    }
}
