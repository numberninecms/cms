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
use NumberNine\Model\PageBuilder\Control\FlexAlignControl;
use NumberNine\Model\PageBuilder\Control\FlexJustifyControl;
use NumberNine\Model\PageBuilder\Control\SelectControl;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @Shortcode(
 *     name="flex_column",
 *     label="Flex column",
 *     container=true,
 *     icon="view_column",
 *     siblingsPosition={"left", "right"},
 *     siblingsShortcodes={"flex_column"}
 * )
 */
final class FlexColumnShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('justify', FlexJustifyControl::class, ['label' => 'Horizontal alignment'])
            ->add('align', FlexAlignControl::class, ['label' => 'Vertical alignment'])
            ->add('type', SelectControl::class, ['label' => 'Size', 'choices' => [
                'initial' => 'Adapt to content',
                '1' => 'Grow or shrink',
                'auto' => 'Automatic',
                'none' => 'Never grow nor shrink',
            ]])
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'justify' => 'start',
            'align' => 'start',
            'type' => '1',
            'content' => '',
        ]);
    }

    public function processParameters(array $parameters): array
    {
        return [
            'justify' => $parameters['justify'],
            'align' => $parameters['align'],
            'type' => $parameters['type'],
            'content' => $parameters['content'],
        ];
    }
}
