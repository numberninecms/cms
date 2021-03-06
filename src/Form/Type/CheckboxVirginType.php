<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

final class CheckboxVirginType extends BaseVirginType
{
    public function getParent(): string
    {
        return CheckboxType::class;
    }
}
