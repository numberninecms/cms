<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\TextBoxShortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Model\PageBuilder\Control\BordersControl;
use NumberNine\Model\PageBuilder\Control\SliderInputControl;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @Shortcode(name="text_box", label="TextBox", container=true, icon="text_fields")
 */
final class TextBoxShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('margin', BordersControl::class)
            ->add('padding', BordersControl::class)
            ->add('width', SliderInputControl::class, ['min' => 0, 'max' => 100, 'suffix' => '%'])
            ->add('scale', SliderInputControl::class, ['min' => 0, 'max' => 500, 'suffix' => '%'])
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'margin' => '',
            'padding' => '',
            'width' => 0,
            'height' => 0,
            'scale' => 100,
            'positionX' => 0,
            'positionY' => 0,
        ]);
    }

    public function processParameters(array $parameters): array
    {
        return [
            'margin' => $parameters['margin'],
            'padding' => $parameters['padding'],
            'width' => $parameters['width'],
            'height' => $parameters['height'],
            'scale' => $parameters['scale'],
            'positionX' => $parameters['positionX'],
            'positionY' => $parameters['positionY'],
        ];
    }
}
