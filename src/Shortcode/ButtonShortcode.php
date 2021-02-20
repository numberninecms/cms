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
use NumberNine\Model\PageBuilder\Control\ButtonToggleControl;
use NumberNine\Model\PageBuilder\Control\OnOffSwitchControl;
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
            ->add('case', ButtonToggleControl::class, ['choices' => [
                'uppercase' => 'ABC',
                'normal' => 'Abc',
            ]])
            ->add('color', SelectControl::class, ['choices' => [
                'primary' => 'Primary',
                'secondary' => 'Secondary',
                'white' => 'White',
            ]])
            ->add('style', SelectControl::class, ['choices' => [
                'default' => 'Default',
                'outline' => 'Outline',
            ]])
            ->add('size', SelectControl::class, ['choices' => [
                'xsmall' => 'X-Small',
                'small' => 'Small',
                'normal' => 'Normal',
                'large' => 'Large',
                'xlarge' => 'X-Large',
                'xxlarge' => 'XX-Large',
            ]])
            ->add('expand', OnOffSwitchControl::class)
            ->add('link')
            ->add('custom_class', null, ['label' => 'Custom CSS classes'])
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content' => '',
            'text' => 'View more...',
            'case' => 'normal',
            'color' => 'primary',
            'style' => 'default',
            'size' => 'normal',
            'expand' => false,
            'link' => '',
            'custom_class' => '',
        ]);
    }

    public function processParameters(array $parameters): array
    {
        return [
            'text' => $parameters['text'],
            'case' => $parameters['case'],
            'color' => $parameters['color'],
            'style' => $parameters['style'],
            'size' => $parameters['size'],
            'expand' => $parameters['expand'],
            'link' => $parameters['link'],
            'custom_class' => $parameters['custom_class'],
        ];
    }
}
