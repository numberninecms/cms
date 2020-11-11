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

use Exception;
use NumberNine\Asset\TagRenderer;
use Twig\Extension\RuntimeExtensionInterface;

final class AssetRuntime implements RuntimeExtensionInterface
{
    private TagRenderer $tagRenderer;

    /**
     * @param TagRenderer $tagRenderer
     */
    public function __construct(TagRenderer $tagRenderer)
    {
        $this->tagRenderer = $tagRenderer;
    }

    /**
     * @param string|null $entryName
     * @return string
     * @throws Exception
     */
    public function renderStylesheetTag(string $entryName = null): string
    {
        return $this->tagRenderer->renderWebpackLinkTags($entryName);
    }

    /**
     * @param string|null $entryName
     * @return string
     * @throws Exception
     */
    public function renderScriptTag(string $entryName = null): string
    {
        return $this->tagRenderer->renderWebpackScriptTags($entryName);
    }

    /**
     * @param string|null $entryName
     * @return string
     * @throws Exception
     */
    public function renderEntryTags(string $entryName = null): string
    {
        return $this->renderStylesheetTag($entryName) . $this->renderScriptTag($entryName);
    }
}
