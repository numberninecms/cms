<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\BlockShortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Content\ContentEntityRenderer;

/**
 * @Shortcode(name="block", label="Block", editable=true, icon="view_day")
 */
final class BlockShortcode extends AbstractShortcode
{
    private ContentEntityRenderer $contentEntityRenderer;

    public function __construct(ContentEntityRenderer $contentEntityRenderer)
    {
        $this->contentEntityRenderer = $contentEntityRenderer;
    }

    public function getBlockContent(BlockShortcodeData $data): string
    {
        if (!$data->getId()) {
            return '[block]';
        }

        return $this->contentEntityRenderer->renderBySlug($data->getId(), false);
    }

    /**
     * @param BlockShortcodeData $data
     */
    public function process($data): void
    {
        $data->setBlockContent($this->getBlockContent($data));
    }
}
