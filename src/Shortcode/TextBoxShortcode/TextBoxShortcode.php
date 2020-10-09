<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\TextBoxShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;

/**
 * @Shortcode(name="text_box", label="TextBox", editable=true, container=true, icon="text_fields")
 */
final class TextBoxShortcode extends AbstractShortcode
{
    /**
     * @Control\Borders(label="Margin")
     */
    public string $margin = '';

    /**
     * @Control\Borders(label="Padding")
     */
    public string $padding = '';

    /**
     * @Control\SliderInput(label="Width", min=0.0, max=100.0, step=1.0, suffix="%")
     */
    public int $width = 0;

    /**
     * @Control\SliderInput(label="Scale", min=0.0, max=500.0, step=1.0, suffix="%")
     */
    public int $scale = 100;

    public string $marginMd = '';
    public string $marginSm = '';
    public int $positionX = 0;
    public int $positionY = 0;
    public int $height = 0;
    public int $heightMd = 0;
    public int $heightSm = 0;
}
