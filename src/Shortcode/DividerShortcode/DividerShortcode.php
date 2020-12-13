<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\DividerShortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Model\PageBuilder\Control\OnOffSwitchControl;
use NumberNine\Model\PageBuilder\Control\SliderControl;
use NumberNine\Model\PageBuilder\Control\TextAlignControl;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function NumberNine\Common\Util\ArrayUtil\array_implode_associative;

/**
 * @Shortcode(name="divider", label="Divider", icon="remove")
 */
final class DividerShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('fullWidth', OnOffSwitchControl::class, ['label' => 'Full width'])
            ->add('align', TextAlignControl::class, ['label' => 'Align'])
            ->add('width', SliderControl::class, [
                'label' => 'Width',
                'min' => 30,
                'max' => 200,
                'suffix' => 'px'
            ])
            ->add('height', SliderControl::class, [
                'label' => 'Height',
                'min' => 1,
                'max' => 10,
                'suffix' => 'px'
            ])
            ->add('margin', SliderControl::class, [
                'label' => 'Margin',
                'min' => 0,
                'max' => 10,
                'step' => 0.1,
                'suffix' => 'em'
            ])
            ->add('color', TextAlignControl::class, ['label' => 'Color'])
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'fullWidth' => false,
            'align' => 'left',
            'width' => 30,
            'height' => 3,
            'margin' => 1.0,
            'color' => 'secondary',
        ]);
    }

    public function processParameters(array $parameters): array
    {
        return [
            'flexAlign' => $this->getFlexAlign($parameters),
            'attributes' => $this->getAttributes($parameters),
        ];
    }

    private function getAttributes(array $parameters): string
    {
        $styles = [];

        if ($parameters['height'] !== 3) {
            $styles['height'] = $parameters['height'] . 'px';
        }

        if ($parameters['fullWidth']) {
            $styles['max-width'] = '100%';
        } elseif ($parameters['width'] !== 30) {
            $styles['max-width'] = $parameters['width'] . 'px';
        }

        if ($parameters['margin'] !== 1.0) {
            $styles['margin-top'] = $parameters['margin'] . 'em';
            $styles['margin-bottom'] = $parameters['margin'] . 'em';
        }

        if ($parameters['color'] !== 'secondary') {
            if (preg_match('/^#(?:[0-9a-fA-F]{3}){1,2}$/', $parameters['color'])) {
                $styles['background-color'] = $parameters['color'];
            } else {
                $styles['background-color'] = sprintf('var(--%s)', $parameters['color']);
            }
        }

        return count($styles) > 0 ? sprintf(' style="%s"', array_implode_associative($styles, ';', ':')) : '';
    }

    private function getFlexAlign(array $parameters): string
    {
        switch ($parameters['align']) {
            case 'center':
                return 'justify-center';

            case 'right':
                return 'justify-end';

            default:
                return 'justify-start';
        }
    }
}
