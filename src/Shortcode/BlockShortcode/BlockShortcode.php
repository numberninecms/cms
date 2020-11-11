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

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Content\ContentEntityRenderer;

/**
 * @Shortcode(name="block", label="Block", editable=true, icon="view_day")
 */
final class BlockShortcode extends AbstractShortcode
{
    /** @var ContentEntityRenderer */
    private $contentEntityRenderer;

    /**
     * @var string
     * @Control\ContentEntity(label="Block", contentType="block")
     */
    public $id;

    /**
     * @param ContentEntityRenderer $contentEntityRenderer
     */
    public function __construct(ContentEntityRenderer $contentEntityRenderer)
    {
        $this->contentEntityRenderer = $contentEntityRenderer;
    }

    public function getBlockContent(): string
    {
        if (!$this->id) {
            return '[block]';
        }

        return $this->contentEntityRenderer->renderBySlug($this->id, false);
    }
}
