<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\FlexRowShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode\Exclude;
use NumberNine\Content\ShortcodeData;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function NumberNine\Common\Util\ArrayUtil\array_implode_associative;
use function NumberNine\Common\Util\ArrayUtil\array_set_if_value_exists;

final class FlexRowShortcodeData extends ShortcodeData
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
     * @Control\Borders(label="Margin", borders={"top", "bottom"})
     */
    protected string $margin;

    /**
     * @Control\Borders(label="Padding")
     */
    protected string $padding;

    protected string $content;

    /**
     * @Exclude("serialization")
     */
    protected function getStyles(): string
    {
        $styles = [];

        array_set_if_value_exists($styles, 'margin', $this->margin);
        array_set_if_value_exists($styles, 'padding', $this->padding);

        return sprintf(' style="%s"', array_implode_associative($styles, ';', ':'));
    }

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content' => '',
            'justify' => 'start',
            'align' => 'start',
            'margin' => '0 auto',
            'padding' => '',
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'align' => $this->align,
            'justify' => $this->justify,
            'styles' => $this->getStyles(),
            'content' => $this->content,
        ];
    }
}
