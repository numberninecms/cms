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
use Symfony\Component\OptionsResolver\OptionsResolver;

class SliderControl extends AbstractPageBuilderFormControl
{
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'suffix' => '',
            'min' => 0,
            'max' => 200,
            'step' => 1,
        ]);

        $resolver->setAllowedTypes('suffix', 'string');
        $resolver->setAllowedTypes('min', ['int', 'float']);
        $resolver->setAllowedTypes('max', ['int', 'float']);
        $resolver->setAllowedTypes('step', ['int', 'float']);
    }
}
