<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\LinkShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Content\ShortcodeData;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class LinkShortcodeData extends ShortcodeData
{
    /**
     * @Control\TextBox(label="URL")
     */
    protected string $href;

    /**
     * @Control\TextBox(label="Title tooltip text")
     */
    protected string $title;

    protected string $content;

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'href' => '',
            'title' => '',
            'content' => '',
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'href' => $this->href,
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
}
