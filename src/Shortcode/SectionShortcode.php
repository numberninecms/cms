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
use NumberNine\Model\PageBuilder\Control\ButtonToggleControl;
use NumberNine\Model\PageBuilder\Control\ColorControl;
use NumberNine\Model\PageBuilder\Control\ImageControl;
use NumberNine\Model\PageBuilder\Control\OnOffSwitchControl;
use NumberNine\Model\PageBuilder\Control\SelectControl;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function NumberNine\Common\Util\ArrayUtil\array_implode_associative;
use function NumberNine\Common\Util\ArrayUtil\array_set_if_value_exists;

/**
 * @Shortcode(
 *     name="section",
 *     label="Section",
 *     container=true,
 *     icon="mdi-rectangle-outline",
 *     siblingsPosition={"top", "bottom"}
 * )
 */
final class SectionShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('container', OnOffSwitchControl::class, ['label' => 'Centered container'])
            ->add('background', ImageControl::class, ['label' => 'Background image'])
            ->add('backgroundSize', SelectControl::class, ['label' => 'Size', 'choices' => [
                'original' => 'Original',
            ]])
            ->add('backgroundColor', ColorControl::class)
            ->add('backgroundPosition')
            ->add('backgroundOverlay', ColorControl::class)
            ->add('height')
            ->add('margin', BordersControl::class)
            ->add('padding', BordersControl::class)
            ->add('color', ButtonToggleControl::class, ['choices' => [
                'light' => 'Light',
                'dark' => 'Dark',
            ]])
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content' => '',
            'container' => false,
            'background' => '',
            'backgroundSize' => 'original',
            'backgroundColor' => '',
            'backgroundPosition' => '',
            'backgroundOverlay' => [],
            'height' => 0,
            'margin' => '0px',
            'padding' => '30px',
            'color' => 'light',
        ]);
    }

    public function processParameters(array $parameters): array
    {
        return [
            'color' => $parameters['color'],
            'styles' => $this->getStyles($parameters),
            'backgroundStyles' => $this->getBackgroundStyles($parameters),
            'content' => $parameters['content'],
        ];
    }

    protected function getStyles(array $parameters): string
    {
        $styles = [];

        array_set_if_value_exists($styles, 'padding', $parameters['padding']);
        array_set_if_value_exists($styles, 'margin', $parameters['margin']);

        return sprintf(' style="%s"', array_implode_associative($styles, ';', ':'));
    }

    protected function getBackgroundStyles(array $parameters): string
    {
        $styles = [];

        array_set_if_value_exists($styles, 'background-color', $parameters['backgroundColor']);
        array_set_if_value_exists(
            $styles,
            'background-image',
            $parameters['background'],
            sprintf("url('%s')", $parameters['background'])
        );
        array_set_if_value_exists($styles, 'background-position', $parameters['backgroundPosition']);

        return sprintf(' style="%s"', array_implode_associative($styles, ';', ':'));
    }
}
