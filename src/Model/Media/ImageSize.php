<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Media;

use Stringable;

final class ImageSize implements Stringable
{
    private ?int $width;
    private ?int $height;

    public function __construct(?int $width = null, ?int $height = null, private bool $crop = false)
    {
        $this->width = $width ?? $height;
        $this->height = $height ?? $width;
    }

    public function __toString(): string
    {
        return sprintf('%sx%s', $this->getWidth(), $this->getHeight());
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function isCrop(): bool
    {
        return $this->crop;
    }
}
