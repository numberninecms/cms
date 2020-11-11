<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\ButtonShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\ShortcodeInterface;

/**
 * @Shortcode(name="button", label="Button", editable=true, icon="crop_7_5")
 */
final class ButtonShortcode extends AbstractShortcode
{
    /**
     * @Control\TextBox(label="Text")
     */
    private ?string $text = null;

    /**
     * @Control\Select(label="Style", choices={
     *     {"label": "Default", "value": "default"},
     *     {"label": "Outline", "value": "outline"},
     * })
     */
    private string $style = 'default';

    /**
     * @Control\TextBox(label="Link")
     */
    public ?string $link = null;

    public function setContent(?string $content): ShortcodeInterface
    {
        if (!$this->text && $content && trim($content)) {
            $this->setText(trim($content));
        }

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
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
