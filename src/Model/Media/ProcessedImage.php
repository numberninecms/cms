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

use Imagine\Image\ImageInterface;

final class ProcessedImage
{
    private array $sizes;

    private array $exif;

    /**
     * ProcessedImage constructor.
     */
    public function __construct(private ImageInterface $image, array $sizes, array $exif)
    {
        $this->sizes = $sizes;
        $this->exif = $exif;
    }

    public function getImage(): ImageInterface
    {
        return $this->image;
    }

    public function getSizes(): array
    {
        return $this->sizes;
    }

    public function getExif(): array
    {
        return $this->exif;
    }
}
