<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Annotation;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("CLASS")
 * @Attributes({
 *     @Attribute("name", type="string"),
 *     @Attribute("areas", type="array"),
 * })
 */
final class Theme implements DescriptorAnnotation
{
    /**
     * @Required
     */
    public string $name;

    public string $mainEntry = 'main';

    public array $areas = [];
}
