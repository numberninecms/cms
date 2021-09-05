<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Event;

use NumberNine\Model\Media\ImageSize;
use Symfony\Contracts\EventDispatcher\Event;

final class ImageSizesEvent extends Event
{
    private array $sizes = [];

    /**
     * ImageSizesEvent constructor.
     */
    public function __construct(array $sizes = [])
    {
        $this->sizes = $sizes;
    }

    public function getSizes(): array
    {
        return $this->sizes;
    }

    public function addSize(string $name, ImageSize $size): self
    {
        $this->sizes[$name] = $size;
        return $this;
    }
}
