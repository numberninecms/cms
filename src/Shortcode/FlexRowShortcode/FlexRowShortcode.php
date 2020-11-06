<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\FlexRowShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode;
use NumberNine\Annotation\Shortcode\Exclude;
use NumberNine\Model\Shortcode\AbstractShortcode;

use function NumberNine\Common\Util\ArrayUtil\array_implode_associative;
use function NumberNine\Common\Util\ArrayUtil\array_set_if_value_exists;

/**
 * @Shortcode(name="flex_row", label="Flex row", editable=true, container=true, icon="view_stream", siblingsPosition={"top", "bottom"})
 */
final class FlexRowShortcode extends AbstractShortcode
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
     * @Control\Borders(label="Margin", borders={"top", "bottom"})
     */
    private string $margin = '0 auto';

    /**
     * @Control\Borders(label="Padding")
     */
    private string $padding = '';

    /**
     * @Exclude("serialization")
     */
    public function getStyles(): string
    {
        $styles = [];

        array_set_if_value_exists($styles, 'margin', $this->margin);
        array_set_if_value_exists($styles, 'padding', $this->padding);

        return sprintf(' style="%s"', array_implode_associative($styles, ';', ':'));
    }

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

    public function getMargin(): string
    {
        return $this->margin;
    }

    public function setMargin(string $margin): void
    {
        $this->margin = $margin;
    }

    public function getPadding(): string
    {
        return $this->padding;
    }

    public function setPadding(string $padding): void
    {
        $this->padding = $padding;
    }
}
