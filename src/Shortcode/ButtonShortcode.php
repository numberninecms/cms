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

use NumberNine\Annotation\Shortcode;
use NumberNine\Model\PageBuilder\Control\SelectControl;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @Shortcode(name="button", label="Button", icon="crop_7_5")
 */
final class ButtonShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('text')
            ->add('style', SelectControl::class, ['choices' => [
                'default' => 'Default',
                'outline' => 'Outline',
            ]])
            ->add('link')
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content' => '',
            'text' => '',
            'style' => 'default',
            'link' => '',
        ]);
    }

    public function processParameters(array $parameters): array
    {
        return [
            'text' => $parameters['text'],
            'style' => $parameters['style'],
            'link' => $parameters['link'],
        ];
    }
}
