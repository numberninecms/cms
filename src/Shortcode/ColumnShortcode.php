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
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @Shortcode(
 *     name="col",
 *     label="Column",
 *     container=true,
 *     icon="mdi-view-week",
 *     siblingsPosition={"left", "right"},
 *     siblingsShortcodes={"col"}
 * )
 */
final class ColumnShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('span', SliderControl::class, ['min' => 0, 'max' => 12])
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content' => '',
            'span' => '12',
        ]);
    }

    public function processParameters(array $parameters): array
    {
        return [
            'flexSpan' => $this->getFlexSpan($parameters),
            'content' => $parameters['content'],
        ];
    }

    private function getFlexSpan(array $parameters): string
    {
        if (preg_match('@1/(\d+?)@', $parameters['span'], $matches)) {
            return 'col-span-' . (int)(12 / $matches[1]);
        }

        if (is_numeric($parameters['span']) && (int)$parameters['span'] <= 12) {
            return 'col-span-' . $parameters['span'];
        }

        return 'col-span-12';
    }
}
