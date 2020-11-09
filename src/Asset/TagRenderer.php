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
use TypeError;

final class TagRenderer
{
    private ThemeStore $themeStore;
    private Packages $packages;
    private ?Request $request;
    private EntrypointLookupCollection $entrypointLookupCollection;

    /**
     * @param ThemeStore $themeStore
     * @param Packages $packages
     * @param RequestStack $requestStack
     * @param EntrypointLookupInterface|EntrypointLookupCollection $entrypointLookupCollection
     */
    public function __construct(ThemeStore $themeStore, Packages $packages, RequestStack $requestStack, $entrypointLookupCollection)
    {
        if ($entrypointLookupCollection instanceof EntrypointLookupInterface) {
            @trigger_error(sprintf('The "$entrypointLookupCollection" argument in method "%s()" must be an instance of EntrypointLookupCollection.', __METHOD__), E_USER_DEPRECATED);

            $this->entrypointLookupCollection = new EntrypointLookupCollection(
                new ServiceLocator(
                    [
                        '_default' => function () use ($entrypointLookupCollection) {
                            return $entrypointLookupCollection;
                        }
                    ]
                )
            );
        } elseif ($entrypointLookupCollection instanceof EntrypointLookupCollection) {
            $this->entrypointLookupCollection = $entrypointLookupCollection;
        } else {
            throw new TypeError('The "$entrypointLookupCollection" argument must be an instance of EntrypointLookupCollection.');
        }

        $this->themeStore = $themeStore;
        $this->packages = $packages;
        $this->request = $requestStack->getMasterRequest();
    }

    /**
     * @param string|null $entryName
     * @param string|null $configName
     * @param bool $ignoreRuntime
     * @return string
     */
    public function renderWebpackScriptTags(string $entryName = null, string $configName = null, bool $ignoreRuntime = false): string
    {
        $entryName = $entryName ?? $this->getThemeMainEntry($this->themeStore->getCurrentTheme());
        $configName = $configName ?? $this->themeStore->getCurrentTheme()->getWebpackConfigName();

        $scriptTags = [];
        foreach ($this->getEntrypointLookup($configName)->getJavaScriptFiles((string)$entryName) as $filename) {
            if ($ignoreRuntime && preg_match('@(:?/runtime.*\.js)$@iU', $filename)) {
                continue;
            }

            $assetPath = htmlentities($this->getAssetPath($filename, $configName));

            if ($this->request) {
                $linkProvider = $this->request->attributes->get('_links', new GenericLinkProvider());
                $this->request->attributes->set('_links', $linkProvider->withLink(
                    (new Link('preload', $assetPath))->withAttribute('as', 'script')
                ));
            }

            $scriptTags[] = sprintf('<script src="%s" defer></script>', $assetPath);
        }

        return implode('', $scriptTags);
    }

    /**
     * @param string $entryName
     * @param string|null $configName
     * @return string
     * @throws Exception
     */
    public function renderWebpackLinkTags(string $entryName = null, string $configName = null): string
    {
        $entryName = $entryName ?? $this->getThemeMainEntry($this->themeStore->getCurrentTheme());
        $configName = $configName ?? $this->themeStore->getCurrentTheme()->getWebpackConfigName();

        $scriptTags = [];
        foreach ($this->getEntrypointLookup($configName)->getCssFiles((string)$entryName) as $filename) {
            $assetPath = htmlentities($this->getAssetPath($filename, $configName));

            if ($this->request) {
                $linkProvider = $this->request->attributes->get('_links', new GenericLinkProvider());
                $this->request->attributes->set('_links', $linkProvider->withLink(
                    (new Link('preload', $assetPath))->withAttribute('as', 'style')
                ));
            }

            $scriptTags[] = sprintf(
                '<link rel="stylesheet" href="%s">',
                $assetPath
            );
        }

        return implode('', $scriptTags);
    }

    /**
     * @param ThemeInterface $theme
     * @return string|null
     */
    private function getThemeMainEntry(ThemeInterface $theme): ?string
    {
        return $theme->getConfiguration()['main_entry'] ?? null;
    }

    private function getAssetPath(string $assetPath, string $packageName = null): string
    {
        return $this->packages->getUrl(
            $assetPath,
            $packageName
        );
    }

    private function getEntrypointLookup(string $buildName): EntrypointLookupInterface
    {
        return $this->entrypointLookupCollection->getEntrypointLookup($buildName);
    }
}
