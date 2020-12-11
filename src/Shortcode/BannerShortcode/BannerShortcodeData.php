<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\BannerShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Content\ShortcodeData;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BannerShortcodeData extends ShortcodeData
{
    /**
     * @Control\TextBox(label="Link")
     */
    protected string $link;

    /**
     * @Control\Color(label="Background color")
     */
    protected string $backgroundColor;

    protected int $height;
    protected int $heightMd;
    protected int $heightSm;

    protected string $content;

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content' => '',
            'link' => '',
            'backgroundColor' => '',
            'height' => 0,
            'heightMd' => 0,
            'heightSm' => 0,
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'content' => $this->content,
        ];
    }
}
