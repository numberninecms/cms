<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Annotation\Form\Control;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 * @Attributes({
 *     @Attribute("label", type="string"),
 *     @Attribute("borders", type="array"),
 * })
 */
final class Borders extends AbstractFormControl
{
    /**
     * Can contain any of these values: top, bottom, left, right
     * @var string[]
     */
    public array $borders = ['top', 'right', 'bottom', 'left'];
}
