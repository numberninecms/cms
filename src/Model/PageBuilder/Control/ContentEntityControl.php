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

class ContentEntityControl extends AbstractPageBuilderFormControl
{
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('contentType');
        $resolver->setAllowedTypes('contentType', 'string');
    }
}
