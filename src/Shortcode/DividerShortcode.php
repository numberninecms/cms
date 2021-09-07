<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode;

use NumberNine\Attribute\Shortcode;
use NumberNine\Model\PageBuilder\Control\BordersControl;
use NumberNine\Model\PageBuilder\Control\ColorControl;
use NumberNine\Model\PageBuilder\Control\OnOffSwitchControl;
use NumberNine\Model\PageBuilder\Control\SliderInputControl;
use NumberNine\Model\PageBuilder\Control\TextAlignmentControl;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

#[Shortcode(name: 'divider', label: 'Divider', icon: 'mdi-minus')]
final class DividerShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('fullWidth', OnOffSwitchControl::class, ['label' => 'Full width'])
            ->add('align', TextAlignmentControl::class, ['label' => 'Align'])
            ->add('width', SliderInputControl::class, [
                'label' => 'Width',
                'min' => 30,
                'max' => 200,
                'suffix' => 'px',
            ])
            ->add('height', SliderInputControl::class, [
                'label' => 'Height',
                'min' => 1,
                'max' => 10,
                'suffix' => 'px',
            ])
            ->add('margin', BordersControl::class, ['borders' => ['top', 'bottom']])
            ->add('color', ColorControl::class, ['label' => 'Color'])
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'fullWidth' => false,
            'align' => 'left',
            'width' => 30,
            'height' => 3,
            'margin' => '20px 0',
            'color' => null,
        ]);
    }

    public function processParameters(array $parameters): array
    {
        return [
            'align' => $parameters['align'],
            'height' => $parameters['height'],
            'width' => $parameters['width'],
            'full_width' => $parameters['fullWidth'],
            'margin' => $parameters['margin'],
            'color' => $parameters['color'],
        ];
    }
}
