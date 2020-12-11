<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\IframeShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Content\ShortcodeData;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class IframeShortcodeData extends ShortcodeData
{
    /**
     * @Control\TextBox(label="Url")
     */
    public string $src;

    /**
     * @Control\TextBox(label="Width")
     */
    public string $width;

    /**
     * @Control\TextBox(label="Height")
     */
    public string $height;

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'src' => '',
            'width' => '100%',
            'height' => '500',
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'src' => $this->src,
            'width' => $this->width,
            'height' => $this->height,
        ];
    }
}
