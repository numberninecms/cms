<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\FlexColumnShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Content\ShortcodeData;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class FlexColumnShortcodeData extends ShortcodeData
{
    /**
     * @Control\FlexJustify(label="Horizontal alignment")
     */
    protected string $justify;

    /**
     * @Control\FlexAlign(label="Vertical alignment")
     */
    protected string $align;

    /**
     * @Control\Select(label="Size", choices={
     *     {"label": "Adapt to content", "value": "initial"},
     *     {"label": "Grow or shrink", "value": "1"},
     *     {"label": "Automatic", "value": "auto"},
     *     {"label": "Never grow nor shrink", "value": "none"},
     * })
     */
    protected string $type;

    protected string $content;

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'justify' => 'start',
            'align' => 'start',
            'type' => '1',
            'content' => '',
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'justify' => $this->justify,
            'align' => $this->align,
            'type' => $this->type,
            'content' => $this->content,
        ];
    }
}
