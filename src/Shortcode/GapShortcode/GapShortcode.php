<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\GapShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;

/**
 * @Shortcode(name="gap", editable=true, label="Gap", icon="height")
 */
final class GapShortcode extends AbstractShortcode
{
    /**
     * @Control\SliderInput(label="Height", suffix="px")
     */
    public int $height = 30;
}
