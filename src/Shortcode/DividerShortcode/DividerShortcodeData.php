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
use NumberNine\Content\ShortcodeData;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function NumberNine\Common\Util\ArrayUtil\array_implode_associative;

final class DividerShortcodeData extends ShortcodeData
{
    /**
     * @Control\OnOffSwitch(label="Full width")
     */
    protected bool $fullWidth;

    /**
     * @Control\TextAlign(label="Align")
     */
    protected string $align;

    /**
     * @Control\Slider(label="Width", min=30.0, max=200.0, suffix="px")
     */
    protected int $width;

    /**
     * @Control\Slider(label="Height", min=1.0, max=10.0, suffix="px")
     */
    protected int $height;

    /**
     * @Control\Slider(label="Margin", min=0.0, max=10.0, step=0.1, suffix="em")
     */
    protected float $margin;

    /**
     * @Control\Color(label="Color")
     */
    protected string $color = 'secondary';

    protected function getAttributes(): string
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

    protected function getFlexAlign(): string
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'fullWidth' => false,
            'align' => 'left',
            'width' => 30,
            'height' => 3,
            'margin' => 1.0,
            'color' => 'secondary',
        ]);
    }

    public function toArray(): array
    {
        return [
            'flexAlign' => $this->getFlexAlign(),
            'attributes' => $this->getAttributes(),
        ];
    }
}
