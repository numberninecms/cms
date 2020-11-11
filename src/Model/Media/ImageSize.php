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

final class ImageSize
{
    private ?int $width;
    private ?int $height;
    private bool $crop;

    /**
     * @param int|null $width
     * @param int|null $height
     * @param bool $crop
     */
    public function __construct(?int $width = null, ?int $height = null, bool $crop = false)
    {
        $this->width = $width ?? $height;
        $this->height = $height ?? $width;
        $this->crop = $crop;
    }

    /**
     * @return int|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @return bool
     */
    public function isCrop(): bool
    {
        return $this->crop;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%sx%s', $this->getWidth(), $this->getHeight());
    }
}
