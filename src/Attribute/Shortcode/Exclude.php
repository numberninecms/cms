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

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
final class Exclude
{
    public const ALL = 'all';
    public const VIEW = 'view';
    public const SERIALIZATION = 'serialization';
    /**
     * @Enum({"all", "view", "serialization"})
     */
    public string $value = 'all';
}
