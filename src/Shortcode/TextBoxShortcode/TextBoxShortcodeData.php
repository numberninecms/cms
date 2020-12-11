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
use NumberNine\Content\ShortcodeData;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TextBoxShortcodeData extends ShortcodeData
{
    /**
     * @Control\Borders(label="Margin")
     */
    protected string $margin = '';

    /**
     * @Control\Borders(label="Padding")
     */
    protected string $padding = '';

    /**
     * @Control\SliderInput(label="Width", min=0.0, max=100.0, step=1.0, suffix="%")
     */
    protected int $width = 0;

    /**
     * @Control\SliderInput(label="Scale", min=0.0, max=500.0, step=1.0, suffix="%")
     */
    protected int $scale = 100;

    protected int $positionX = 0;
    protected int $positionY = 0;
    protected int $height = 0;

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'margin' => '',
            'padding' => '',
            'width' => 0,
            'height' => 0,
            'scale' => 100,
            'positionX' => 0,
            'positionY' => 0,
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'margin' => $this->margin,
            'padding' => $this->padding,
            'width' => $this->width,
            'height' => $this->height,
            'scale' => $this->scale,
            'positionX' => $this->positionX,
            'positionY' => $this->positionY,
        ];
    }
}
