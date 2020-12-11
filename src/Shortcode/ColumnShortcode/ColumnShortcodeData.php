<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\ColumnShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Content\ShortcodeData;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ColumnShortcodeData extends ShortcodeData
{
    /**
     * @Control\Slider(label="Width", min=0.0, max=12.0, step=1.0)
     */
    protected string $span;

    protected string $content;

    protected function getFlexSpan(): string
    {
        if (preg_match('@1/(\d+?)@', $this->span, $matches)) {
            return 'col-span-' . (int)(12 / $matches[1]);
        }

        if (is_numeric($this->span) && (int)$this->span <= 12) {
            return 'col-span-' . $this->span;
        }

        return 'col-span-12';
    }

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content' => '',
            'span' => '12',
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'flexSpan' => $this->getFlexSpan(),
            'content' => $this->content,
        ];
    }
}
