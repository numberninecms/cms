<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\General;

use NumberNine\Annotation\DescriptorAnnotation;

interface Descriptor
{
    public function __construct(DescriptorAnnotation $metadata);
}
