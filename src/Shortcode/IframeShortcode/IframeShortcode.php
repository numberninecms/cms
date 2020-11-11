<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\IframeShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;

/**
 * @Shortcode(name="iframe", label="Iframe", editable=true, icon="create")
 */
final class IframeShortcode extends AbstractShortcode
{
    /**
     * @Control\TextBox(label="Url")
     */
    public string $src = '';

    /**
     * @Control\TextBox(label="Width")
     */
    public string $width = '100%';

    /**
     * @Control\TextBox(label="Height")
     */
    public string $height = '500';
}
