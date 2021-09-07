<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Attribute\Shortcode;

use Attribute;
use Doctrine\Common\Annotations\Annotation\Enum;

#[Attribute(Attribute::TARGET_CLASS)]
final class ExclusionPolicy
{
    public const NONE = 'none';
    public const ALL = 'all';
    /**
     * @Enum({"all", "none"})
     */
    public string $value = 'none';
}
