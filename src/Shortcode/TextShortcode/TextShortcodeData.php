<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\TextShortcode;

use NumberNine\Content\ShortcodeData;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextShortcodeData extends ShortcodeData
{
    /**
     * @Control\Editor(label="Content")
     */
    protected string $content;

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content' => ''
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'content' => $this->content,
        ];
    }
}
