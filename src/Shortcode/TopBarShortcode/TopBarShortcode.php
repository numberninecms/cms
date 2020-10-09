<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\TopBarShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;

/**
 * @Shortcode(name="topbar", label="Top Bar", editable=true, container=true)
 */
final class TopBarShortcode extends AbstractShortcode
{
    /**
     * @Control\OnOffSwitch(label="Enable Top Bar")
     */
    private bool $enabled = true;

    /**
     * @Control\SliderInput(label="Height", min=30.0, max=200.0, step=1.0, suffix="px")
     */
    private int $height = 30;

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnable(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
}
