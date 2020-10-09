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
    /** @var ImageInterface */
    private $image;

    /** @var array */
    private $sizes;

    /** @var array */
    private $exif;

    /**
     * ProcessedImage constructor.
     * @param ImageInterface $image
     * @param array $sizes
     */
    public function __construct(ImageInterface $image, array $sizes, array $exif)
    {
        $this->image = $image;
        $this->sizes = $sizes;
        $this->exif = $exif;
    }

    /**
     * @return ImageInterface
     */
    public function getImage(): ImageInterface
    {
        return $this->image;
    }

    /**
     * @return array
     */
    public function getSizes(): array
    {
        return $this->sizes;
    }

    /**
     * @return array
     */
    public function getExif(): array
    {
        return $this->exif;
    }
}
