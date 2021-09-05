<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\PageBuilder\Control;

use NumberNine\Model\PageBuilder\AbstractPageBuilderFormControl;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractSelectControl extends AbstractPageBuilderFormControl
{
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('choices');
        $resolver->setAllowedTypes('choices', 'array');
        $resolver->setNormalizer('choices', static function (Options $options, array $choices): array {
            return array_map(
                static fn ($choice, $label): array => ['label' => $label, 'value' => $choice],
                array_keys($choices),
                $choices,
            );
        });
    }
}
