<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\SectionShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Form\Responsive;
use NumberNine\Annotation\Shortcode\Exclude;
use NumberNine\Content\ShortcodeData;
use NumberNine\Model\Shortcode\ResponsiveShortcodeInterface;
use NumberNine\Model\Shortcode\ResponsiveShortcodeTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function NumberNine\Common\Util\ArrayUtil\array_implode_associative;
use function NumberNine\Common\Util\ArrayUtil\array_set_if_value_exists;

final class SectionShortcodeData extends ShortcodeData implements ResponsiveShortcodeInterface
{
    use ResponsiveShortcodeTrait;

    /**
     * @Control\OnOffSwitch(label="Centered container")
     */
    protected bool $container;

    /**
     * @Control\Image(label="Background image")
     */
    protected string $background;

    /**
     * @Control\Select(label="Size", choices={
     *     {"label": "Original", "value": "original"},
     * })
     */
    protected string $backgroundSize;

    /**
     * @Control\Color(label="Background color")
     */
    protected string $backgroundColor;

    /**
     * @Control\TextBox(label="Background position")
     */
    protected string $backgroundPosition;

    /**
     * @Control\Color(label="Background overlay")
     * @Responsive
     */
    protected array $backgroundOverlay;

//    protected string $backgroundOverlayMd = '';
//    protected string $backgroundOverlaySm = '';
    protected int $height;
    protected int $heightMd;
    protected int $heightSm;

    /**
     * @Control\Borders(label="Margin")
     */
    protected string $margin;

    /**
     * @Control\Borders(label="Padding")
     */
    protected string $padding;

    /**
     * @Control\ButtonToggle(label="Color", choices={
     *     {"label": "Light", "value": "light"},
     *     {"label": "Dark", "value": "dark"},
     * })
     */
    protected string $color;

    protected string $content;

    /**
     * @Exclude("serialization")
     */
    protected function getClasses(): string
    {
        $classes = array_merge(
            ['section', $this->color],
            in_array('sm', $this->getHiddenViewSizes(), true) ? ['hidden'] : [],
            array_map(fn($size) => "$size:block", array_filter($this->visibility, fn($size) => $size !== 'sm')),
        );

        return sprintf(' class="%s"', implode(' ', $classes));
    }

    /**
     * @Exclude("serialization")
     */
    protected function getStyles(): string
    {
        $styles = [];

        array_set_if_value_exists($styles, 'padding', $this->padding);
        array_set_if_value_exists($styles, 'margin', $this->margin);

        return sprintf(' style="%s"', array_implode_associative($styles, ';', ':'));
    }

    /**
     * @Exclude("serialization")
     */
    protected function getBackgroundStyles(): string
    {
        $styles = [];

        array_set_if_value_exists($styles, 'background-color', $this->backgroundColor);
        array_set_if_value_exists(
            $styles,
            'background-image',
            $this->background,
            sprintf("url('%s')", $this->background)
        );
        array_set_if_value_exists($styles, 'background-position', $this->backgroundPosition);

        return sprintf(' style="%s"', array_implode_associative($styles, ';', ':'));
    }

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content' => '',
            'container' => false,
            'background' => '',
            'backgroundSize' => 'original',
            'backgroundColor' => '',
            'backgroundPosition' => '',
            'backgroundOverlay' => [],
            'height' => 0,
            'heightMd' => 0,
            'heightSm' => 0,
            'margin' => '0px',
            'padding' => '30px',
            'color' => 'light',
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'color' => $this->color,
            'styles' => $this->getStyles(),
            'backgroundStyles' => $this->getBackgroundStyles(),
            'content' => $this->content,
        ];
    }
}
