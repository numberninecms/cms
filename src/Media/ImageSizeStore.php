<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Media;

final class ImageSizeStore
{
    private array $imageSizes = [];

    /**
     * @return array
     */
    public function getImageSizes(): array
    {
        return $this->imageSizes;
    }

    /**
     * @param array $imageSizes
     */
    public function setImageSizes(array $imageSizes): void
    {
        $this->imageSizes = $imageSizes;
    }
}
