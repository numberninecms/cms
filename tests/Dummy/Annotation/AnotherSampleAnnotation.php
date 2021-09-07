<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\Tests\Dummy\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;

/**
 * @Annotation
 * @Target({"CLASS", "PROPERTY"})
 * @Attributes({
 *     @Attribute("name", type="string"),
 *     @Attribute("value", type="bool")
 * })
 */
final class AnotherSampleAnnotation
{
    public string $category;
    public bool $nullable;
}
