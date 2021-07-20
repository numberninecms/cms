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
use NumberNine\Model\PageBuilder\Control\BordersControl;
use NumberNine\Model\PageBuilder\Control\ColorControl;
use NumberNine\Model\PageBuilder\Control\SelectControl;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @Shortcode(name="title", label="Title", icon="mdi-format-title")
 */
final class TitleShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('text', null, ['label' => 'Title text'])
            ->add('tag', SelectControl::class, ['choices' => [
                'h1' => 'Header 1',
                'h2' => 'Header 2',
                'h3' => 'Header 3',
                'h4' => 'Header 4',
                'h5' => 'Header 5',
                'h6' => 'Header 6',
                'div' => 'Standard',
            ]])
            ->add('size', SelectControl::class, ['choices' => [
                'xsmall' => 'X-Small',
                'small' => 'Small',
                'normal' => 'Normal',
                'large' => 'Large',
                'xlarge' => 'X-Large',
                'xxlarge' => 'XX-Large',
            ]])
            ->add('color', ColorControl::class)
            ->add('style', SelectControl::class, ['choices' => [
                'center' => 'Center',
                'left' => 'Left',
            ]])
            ->add('margin', BordersControl::class)
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content' => '',
            'text' => 'Title',
            'tag' => 'h2',
            'color' => null,
            'style' => 'center',
            'size' => 'normal',
            'margin' => '30px 0px',
        ]);
    }

    public function processParameters(array $parameters): array
    {
        return [
            'tag' => $parameters['tag'],
            'color' => $parameters['color'],
            'style' => $parameters['style'],
            'text' => !$parameters['text'] && trim($parameters['content'])
                ? trim($parameters['content'])
                : $parameters['text'],
            'size' => $parameters['size'],
            'margin' => $parameters['margin'],
        ];
    }
}
