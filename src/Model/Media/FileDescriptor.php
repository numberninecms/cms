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
    /** @var string */
    private $originalFilename;

    /** @var string */
    private $newFilename;

    /** @var string */
    private $slugifiedFilename;

    /**
     * @return string
     */
    public function getOriginalFilename(): string
    {
        return $this->originalFilename;
    }

    /**
     * @param string $originalFilename
     * @return FileDescriptor
     */
    public function setOriginalFilename(string $originalFilename): FileDescriptor
    {
        $this->originalFilename = $originalFilename;
        return $this;
    }

    /**
     * @return string
     */
    public function getNewFilename(): string
    {
        return $this->newFilename;
    }

    /**
     * @param string $newFilename
     * @return FileDescriptor
     */
    public function setNewFilename(string $newFilename): FileDescriptor
    {
        $this->newFilename = $newFilename;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlugifiedFilename(): string
    {
        return $this->slugifiedFilename;
    }

    /**
     * @param string $slugifiedFilename
     * @return FileDescriptor
     */
    public function setSlugifiedFilename(string $slugifiedFilename): FileDescriptor
    {
        $this->slugifiedFilename = $slugifiedFilename;
        return $this;
    }

    /**
     * @return string
     */
    public function getUploadDirectory(): string
    {
        return dirname($this->getNewFilename());
    }
}
