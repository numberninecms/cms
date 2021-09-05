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

final class FileDescriptor
{
    private ?string $originalFilename = null;

    private ?string $newFilename = null;

    private ?string $slugifiedFilename = null;

    public function getOriginalFilename(): string
    {
        return $this->originalFilename;
    }

    public function setOriginalFilename(string $originalFilename): FileDescriptor
    {
        $this->originalFilename = $originalFilename;
        return $this;
    }

    public function getNewFilename(): string
    {
        return $this->newFilename;
    }

    public function setNewFilename(string $newFilename): FileDescriptor
    {
        $this->newFilename = $newFilename;
        return $this;
    }

    public function getSlugifiedFilename(): string
    {
        return $this->slugifiedFilename;
    }

    public function setSlugifiedFilename(string $slugifiedFilename): FileDescriptor
    {
        $this->slugifiedFilename = $slugifiedFilename;
        return $this;
    }

    public function getUploadDirectory(): string
    {
        return dirname($this->getNewFilename());
    }
}
