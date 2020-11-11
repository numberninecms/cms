<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\FlexColumnShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;

/**
 * @Shortcode(
 *     name="flex_column",
 *     label="Flex column",
 *     editable=true,
 *     container=true,
 *     icon="view_column",
 *     siblingsPosition={"left", "right"},
 *     siblingsShortcodes={"flex_column"}
 * )
 */
final class FlexColumnShortcode extends AbstractShortcode
{
    /**
     * @Control\FlexJustify(label="Horizontal alignment")
     */
    private string $justify = 'start';

    /**
     * @Control\FlexAlign(label="Vertical alignment")
     */
    private string $align = 'start';

    /**
     * @Control\Select(label="Size", choices={
     *     {"label": "Adapt to content", "value": "initial"},
     *     {"label": "Grow or shrink", "value": "1"},
     *     {"label": "Automatic", "value": "auto"},
     *     {"label": "Never grow nor shrink", "value": "none"},
     * })
     */
    private string $type = '1';

    public function getJustify(): string
    {
        return $this->justify;
    }

    public function setJustify(string $justify): void
    {
        $this->justify = $justify;
    }

    public function getAlign(): string
    {
        return $this->align;
    }

    public function setAlign(string $align): void
    {
        $this->align = $align;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
