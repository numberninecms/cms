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
    private bool $container;

    /**
     * @Control\Image(label="Background image")
     */
    private string $background;

    /**
     * @Control\Select(label="Size", choices={
     *     {"label": "Original", "value": "original"},
     * })
     */
    private string $backgroundSize;

    /**
     * @Control\Color(label="Background color")
     */
    private string $backgroundColor;

    /**
     * @Control\TextBox(label="Background position")
     */
    private string $backgroundPosition;

    /**
     * @Control\Color(label="Background overlay")
     * @Responsive
     */
    private array $backgroundOverlay;

//    private string $backgroundOverlayMd = '';
//    private string $backgroundOverlaySm = '';
    private int $height;
    private int $heightMd;
    private int $heightSm;

    /**
     * @Control\Borders(label="Margin")
     */
    private string $margin;

    /**
     * @Control\Borders(label="Padding")
     */
    private string $padding;

    /**
     * @Control\ButtonToggle(label="Color", choices={
     *     {"label": "Light", "value": "light"},
     *     {"label": "Dark", "value": "dark"},
     * })
     */
    private string $color;

    private string $content;

    /**
     * @Exclude("serialization")
     */
    public function getClasses(): string
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
    public function getStyles(): string
    {
        $styles = [];

        array_set_if_value_exists($styles, 'padding', $this->padding);
        array_set_if_value_exists($styles, 'margin', $this->margin);

        return sprintf(' style="%s"', array_implode_associative($styles, ';', ':'));
    }

    /**
     * @Exclude("serialization")
     */
    public function getBackgroundStyles(): string
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

    public function configureOptions(OptionsResolver $resolver): void
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

    public function toArray(): array
    {
        return [
            'color' => $this->color,
            'styles' => $this->getStyles(),
            'backgroundStyles' => $this->getBackgroundStyles(),
            'content' => $this->content,
        ];
    }
}
