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
use NumberNine\Model\PageBuilder\Control\SliderControl;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @Shortcode(name="grid", container=true, label="Grid", icon="mdi-grid")
 */
final class GridShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('columnsCount', SliderControl::class, ['min' => 1, 'max' => 12])
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content' => '',
            'columnsCount' => 3,
        ]);

        $resolver->setAllowedTypes('columnsCount', ['int', 'float', 'string']);

        $resolver->setNormalizer('columnsCount', static function (Options $options, $value) {
            return is_numeric($value) ? max(1, min(12, (int)$value)) : 3;
        });
    }

    public function processParameters(array $parameters): array
    {
        return [
            'content' => $parameters['content'],
            'columns_count' => $parameters['columnsCount'],
        ];
    }
}
