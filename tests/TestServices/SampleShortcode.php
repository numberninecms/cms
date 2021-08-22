<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\TestServices;

use NumberNine\Annotation\Shortcode;
use NumberNine\Model\PageBuilder\Control\SliderControl;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @Shortcode(name="sample", label="Sample", icon="arrows-alt-v")
 */
final class SampleShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('height', SliderControl::class, ['suffix' => 'px'])
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'height' => 30,
        ]);

        $resolver->setAllowedTypes('height', ['int', 'float', 'string']);

        $resolver->setNormalizer('height', static function (Options $options, $value) {
            return is_numeric($value) ? (float)$value : 30;
        });
    }

    public function processParameters(array $parameters): array
    {
        return [
            'height' => $parameters['height'],
        ];
    }
}
