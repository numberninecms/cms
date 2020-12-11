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
use NumberNine\Content\ShortcodeData;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TitleShortcodeData extends ShortcodeData
{
    /**
     * @Control\TextBox(label="Title")
     */
    protected string $text;

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
    protected string $tag;

    /**
     * @Control\Color(label="Color")
     */
    protected ?string $color;

    /**
     * @Control\Select(label="Style", choices={
     *     {"label": "Center", "value": "center"},
     *     {"label": "Left", "value": "left"},
     * })
     */
    protected string $style;

    protected string $content;

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content' => '',
            'text' => '',
            'tag' => 'h2',
            'color' => null,
            'style' => 'center',
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'tag' => $this->tag,
            'color' => $this->color,
            'text' => $this->text,
        ];
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
