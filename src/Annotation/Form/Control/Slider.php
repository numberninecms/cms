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
 */
class Slider extends AbstractFormControl
{
    public string $suffix = '';

    /** @var mixed */
    public $min = 0;

    /** @var mixed */
    public $max = 200;

    /** @var mixed */
    public $step = 1;
}
