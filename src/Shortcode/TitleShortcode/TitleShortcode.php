<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\TitleShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\ShortcodeInterface;

/**
 * @Shortcode(name="title", editable=true, label="Title", icon="title")
 */
final class TitleShortcode extends AbstractShortcode
{
    /**
     * @Control\TextBox(label="Title")
     */
    private string $text = '';

    /**
     * @Control\Select(label="Tag", choices={
     *     {"label": "Header 1", "value": "h1"},
     *     {"label": "Header 2", "value": "h2"},
     *     {"label": "Header 3", "value": "h3"},
     *     {"label": "Header 4", "value": "h4"},
     *     {"label": "Header 5", "value": "h5"},
     *     {"label": "Header 6", "value": "h6"},
     * })
     */
    private string $tag = 'h2';

    /**
     * @Control\Color(label="Color")
     */
    private ?string $color = null;

    /**
     * @Control\Select(label="Style", choices={
     *     {"label": "Center", "value": "center"},
     *     {"label": "Left", "value": "left"},
     * })
     */
    private string $style = 'center';

    public function setContent(?string $content): ShortcodeInterface
    {
        if (!$this->text && trim((string)$content)) {
            $this->setText(trim((string)$content));
        }

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function setTag(string $tag): void
    {
        $this->tag = $tag;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): void
    {
        $this->color = $color;
    }

    public function getStyle(): string
    {
        return $this->style;
    }

    public function setStyle(string $style): void
    {
        $this->style = $style;
    }
}
