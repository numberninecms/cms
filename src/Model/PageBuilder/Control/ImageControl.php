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
use NumberNine\Model\PageBuilder\FormControlCriteria;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ImageControl extends AbstractPageBuilderFormControl
{
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['fallback_criteria']);
        $resolver->setAllowedTypes('fallback_criteria', FormControlCriteria::class);
    }
}
