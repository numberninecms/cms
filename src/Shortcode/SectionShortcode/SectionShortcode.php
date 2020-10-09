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
use NumberNine\Annotation\Shortcode;
use NumberNine\Annotation\Shortcode\Exclude;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\ResponsiveShortcodeInterface;
use NumberNine\Model\Shortcode\ResponsiveShortcodeTrait;

use function NumberNine\Util\ArrayUtil\array_implode_associative;
use function NumberNine\Util\ArrayUtil\array_set_if_value_exists;

/**
 * @Shortcode(name="section", label="Section", container=true, editable=true, icon="web", siblingsPosition={"top", "bottom"})
 */
final class SectionShortcode extends AbstractShortcode implements ResponsiveShortcodeInterface
{
    use ResponsiveShortcodeTrait;

    /**
     * @Control\OnOffSwitch(label="Centered container")
     */
    private bool $container = false;

    /**
     * @Control\Image(label="Background image")
     */
    private string $background = '';

    /**
     * @Control\Select(label="Size", choices={
     *     {"label": "Original", "value": "original"},
     * })
     */
    private string $backgroundSize = 'original';

    /**
     * @Control\Color(label="Background color")
     */
    private string $backgroundColor = '';

    /**
     * @Control\TextBox(label="Background position")
     */
    private string $backgroundPosition = '';

    /**
     * @Control\Color(label="Background overlay")
     * @Responsive
     */
    private array $backgroundOverlay = [];

//    private string $backgroundOverlayMd = '';
//    private string $backgroundOverlaySm = '';
    private int $height = 0;
    private int $heightMd = 0;
    private int $heightSm = 0;

    /**
     * @Control\Borders(label="Margin")
     */
    private string $margin = '0px';

    /**
     * @Control\Borders(label="Padding")
     */
    private string $padding = '30px';

    /**
     * @Control\ButtonToggle(label="Color", choices={
     *     {"label": "Light", "value": "light"},
     *     {"label": "Dark", "value": "dark"},
     * })
     */
    private string $color = 'light';

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
        array_set_if_value_exists($styles, 'background-image', $this->background, sprintf("url('%s')", $this->background));
        array_set_if_value_exists($styles, 'background-position', $this->backgroundPosition);

        return sprintf(' style="%s"', array_implode_associative($styles, ';', ':'));
    }

    public function isContainer(): bool
    {
        return $this->container;
    }

    public function setContainer(bool $container): void
    {
        $this->container = $container;
    }

    public function getBackground(): string
    {
        return $this->background;
    }

    public function setBackground(string $background): void
    {
        $this->background = $background;
    }

    public function getBackgroundSize(): string
    {
        return $this->backgroundSize;
    }

    public function setBackgroundSize(string $backgroundSize): void
    {
        $this->backgroundSize = $backgroundSize;
    }

    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(string $backgroundColor): void
    {
        $this->backgroundColor = $backgroundColor;
    }

    public function getBackgroundPosition(): string
    {
        return $this->backgroundPosition;
    }

    public function setBackgroundPosition(string $backgroundPosition): void
    {
        $this->backgroundPosition = $backgroundPosition;
    }

    /**
     * @return array
     */
    public function getBackgroundOverlay(): array
    {
        return $this->backgroundOverlay;
    }

    /**
     * @param array $backgroundOverlay
     */
    public function setBackgroundOverlay(array $backgroundOverlay): void
    {
        $this->backgroundOverlay = $backgroundOverlay;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    public function getHeightMd(): int
    {
        return $this->heightMd;
    }

    public function setHeightMd(int $heightMd): void
    {
        $this->heightMd = $heightMd;
    }

    public function getHeightSm(): int
    {
        return $this->heightSm;
    }

    public function setHeightSm(int $heightSm): void
    {
        $this->heightSm = $heightSm;
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

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }
}
