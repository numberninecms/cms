<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class NormalizationContext
{
    public function __construct(public array $groups)
    {
    }
}
