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
use NumberNine\Model\PageBuilder\Control\SliderControl;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

#[Shortcode(name: 'grid_element', label: 'Grid element', container: true, icon: 'mdi-view-grid-plus')]
final class GridElementShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('span', SliderControl::class, ['min' => 1, 'max' => 12])
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content' => '',
            'span' => 1,
        ]);

        $resolver->setAllowedTypes('span', ['int', 'float', 'string']);

        $resolver->setNormalizer('span', static function (Options $options, $value): int {
            return is_numeric($value) ? max(1, min(12, (int) $value)) : 1;
        });
    }

    public function processParameters(array $parameters): array
    {
        return [
            'content' => $parameters['content'],
            'span' => $parameters['span'],
        ];
    }
}
