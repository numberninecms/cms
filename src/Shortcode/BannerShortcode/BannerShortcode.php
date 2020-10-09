<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\BannerShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;

/**
 * @Shortcode(name="banner", label="Banner", editable=true, container=true, icon="web")
 */
final class BannerShortcode extends AbstractShortcode
{
    /**
     * @Control\TextBox(label="Link")
     */
    public string $link = '';

    /**
     * @Control\Color(label="Background color")
     */
    public string $backgroundColor = '';

    public int $height = 0;
    public int $heightMd = 0;
    public int $heightSm = 0;
}
