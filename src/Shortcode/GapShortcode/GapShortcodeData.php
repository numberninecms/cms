<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\GapShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Content\ShortcodeData;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class GapShortcodeData extends ShortcodeData
{
    /**
     * @Control\SliderInput(label="Height", suffix="px")
     */
    protected int $height;

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'height' => 30,
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'height' => $this->height,
        ];
    }
}
