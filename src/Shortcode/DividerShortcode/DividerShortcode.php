<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\DividerShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\CacheableContent;

use function NumberNine\Common\Util\ArrayUtil\array_implode_associative;

/**
 * @Shortcode(name="divider", label="Divider", editable=true, icon="remove")
 */
final class DividerShortcode extends AbstractShortcode implements CacheableContent
{
    /**
     * @Control\OnOffSwitch(label="Full width")
     * @var bool
     */
    public $fullWidth = false;

    /**
     * @Control\TextAlign(label="Align")
     * @var string
     */
    public $align = 'left';

    /**
     * @Control\Slider(label="Width", min=30.0, max=200.0, suffix="px")
     * @var int
     */
    public $width = 30;

    /**
     * @Control\Slider(label="Height", min=1.0, max=10.0, suffix="px")
     * @var int
     */
    public $height = 3;

    /**
     * @Control\Slider(label="Margin", min=0.0, max=10.0, step=0.1, suffix="em")
     * @var float
     */
    public $margin = 1.0;

    /**
     * @Control\Color(label="Color")
     */
    public string $color = 'secondary';

    public function getAttributes(): string
    {
        $styles = [];

        if ($this->height !== 3) {
            $styles['height'] = $this->height . 'px';
        }

        if ($this->fullWidth) {
            $styles['max-width'] = '100%';
        } elseif ($this->width !== 30) {
            $styles['max-width'] = $this->width . 'px';
        }

        if ($this->margin !== 1.0) {
            $styles['margin-top'] = $this->margin . 'em';
            $styles['margin-bottom'] = $this->margin . 'em';
        }

        if ($this->color !== 'secondary') {
            if (preg_match('/^#(?:[0-9a-fA-F]{3}){1,2}$/', $this->color)) {
                $styles['background-color'] = $this->color;
            } else {
                $styles['background-color'] = sprintf('var(--%s)', $this->color);
            }
        }

        return count($styles) > 0 ? sprintf(' style="%s"', array_implode_associative($styles, ';', ':')) : '';
    }

    public function getFlexAlign(): string
    {
        switch ($this->align) {
            case 'center':
                return 'justify-center';

            case 'right':
                return 'justify-end';

            default:
                return 'justify-start';
        }
    }

    /**
     * @Shortcode\Exclude
     */
    public function getCacheIdentifier(): string
    {
        return sprintf(
            'shortcode_divider_%s_%d_%d_%f_%s_%d',
            $this->align,
            $this->width,
            $this->height,
            $this->margin,
            $this->color,
            $this->fullWidth
        );
    }
}
