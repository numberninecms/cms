<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\LogoShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Content\ShortcodeData;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class LogoShortcodeData extends ShortcodeData
{
    /**
     * @Control\Image(label="Logo image")
     */
    private ?string $logoImage = '';

    /**
     * @Control\TextBox(label="Fallback text")
     */
    private ?string $fallbackText = '';

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'logoImage' => '',
            'fallbackText' => '',
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'logoImage' => $this->logoImage,
            'fallbackText' => $this->fallbackText,
        ];
    }
}
