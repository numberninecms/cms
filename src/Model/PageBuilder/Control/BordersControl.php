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

use function NumberNine\Common\Util\ArrayUtil\in_array_all;

class BordersControl extends AbstractPageBuilderFormControl
{
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'borders' => ['top', 'right', 'bottom', 'left'],
        ]);

        $resolver->setAllowedTypes('borders', 'array');
        $resolver->addAllowedValues('borders', function (array $value): bool {
            return in_array_all($value, ['top', 'right', 'bottom', 'left']);
        });
    }
}
