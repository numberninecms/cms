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
use NumberNine\Model\PageBuilder\Control\ButtonToggleControl;
use NumberNine\Model\PageBuilder\Control\HorizontalAlignmentControl;
use NumberNine\Model\PageBuilder\Control\VerticalAlignmentControl;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\PageBuilder\Position;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function NumberNine\Common\Util\ArrayUtil\array_implode_associative;
use function NumberNine\Common\Util\ArrayUtil\array_set_if_value_exists;

#[Shortcode(name: 'container', label: 'Container', container: true, icon: 'mdi-table-row', siblingsPosition: [
    Position::TOP,
    Position::BOTTOM,
])]
final class ContainerShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('orientation', ButtonToggleControl::class, ['choices' => [
                'horizontal' => 'Horizontal',
                'vertical' => 'Vertical',
            ]])
            ->add('justify', HorizontalAlignmentControl::class, ['label' => 'Horizontal alignment'])
            ->add('align', VerticalAlignmentControl::class, ['label' => 'Vertical alignment'])
            ->add('margin', BordersControl::class, ['borders' => ['top', 'bottom']])
            ->add('padding', BordersControl::class)
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'content' => '',
            'orientation' => 'horizontal',
            'justify' => 'start',
            'align' => 'start',
            'margin' => '0 auto',
            'padding' => '',
        ]);

        $resolver->setAllowedValues('orientation', ['horizontal', 'vertical']);
        $resolver->setAllowedValues('justify', ['start', 'center', 'end', 'between', 'around']);
        $resolver->setAllowedValues('align', ['start', 'center', 'end', 'stretch', 'baseline']);

        $resolver->setNormalizer('margin', static function (Options $options, string $value): string {
            if (!preg_match(self::INLINE_BORDERS_PATTERN, trim($value))) {
                return '0 auto';
            }

            return trim($value);
        });

        $resolver->setNormalizer('padding', static function (Options $options, string $value): string {
            if (!preg_match(self::INLINE_BORDERS_PATTERN, trim($value))) {
                return '';
            }

            return trim($value);
        });
    }

    public function processParameters(array $parameters): array
    {
        return [
            'align' => $parameters['align'],
            'justify' => $parameters['justify'],
            'styles' => $this->getStyles($parameters),
            'content' => $parameters['content'],
        ];
    }

    private function getStyles(array $parameters): string
    {
        $styles = [];

        array_set_if_value_exists($styles, 'margin', $parameters['margin']);
        array_set_if_value_exists($styles, 'padding', $parameters['padding']);

        return sprintf(' style="%s"', array_implode_associative($styles, ';', ':'));
    }
}
