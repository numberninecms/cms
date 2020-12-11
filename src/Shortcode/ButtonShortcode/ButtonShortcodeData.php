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
use NumberNine\Content\ShortcodeData;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ButtonShortcodeData extends ShortcodeData
{
    /**
     * @Control\TextBox(label="Text")
     */
    protected string $text;

    /**
     * @Control\Select(label="Style", choices={
     *     {"label": "Default", "value": "default"},
     *     {"label": "Outline", "value": "outline"},
     * })
     */
    protected string $style;

    /**
     * @Control\TextBox(label="Link")
     */
    protected string $link;

    protected string $content;

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content' => '',
            'text' => '',
            'style' => 'default',
            'link' => '',
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'text' => $this->text,
            'style' => $this->style,
            'link' => $this->link,
        ];
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }
}
