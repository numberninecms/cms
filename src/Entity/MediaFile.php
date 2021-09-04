<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Entity;

use Doctrine\ORM\Mapping as ORM;
use NumberNine\Annotation\NormalizationContext;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="NumberNine\Repository\MediaFileRepository")
 * @ORM\Table(name="mediafile")
 * @NormalizationContext(groups={"content_entity_get", "web_access_get", "author_get", "media_file_get"})
 */
class MediaFile extends ContentEntity
{
    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     * @Groups("media_file_get")
     */
    private ?string $path = null;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private ?string $remoteUrl = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("media_file_get")
     */
    private ?int $fileSize = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("media_file_get")
     */
    private ?int $width = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("media_file_get")
     */
    private ?int $height = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("media_file_get")
     */
    private ?int $duration = null;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Groups("media_file_get")
     */
    private array $sizes = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Groups("media_file_get")
     */
    private array $exif = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private array $keywords = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $credit = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("media_file_get")
     */
    private ?string $caption = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("media_file_get")
     */
    private ?string $copyright = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("media_file_get")
     */
    private ?string $alternativeText = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("media_file_get")
     */
    private ?string $mimeType = null;

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getRemoteUrl(): ?string
    {
        return $this->remoteUrl;
    }

    public function setRemoteUrl(string $remoteUrl): self
    {
        $this->remoteUrl = $remoteUrl;

        return $this;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    public function setFileSize(int $fileSize): self
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    public function getExif(): array
    {
        return $this->exif;
    }

    public function setExif(array $exif): self
    {
        $this->exif = $exif;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getSizes(): array
    {
        return $this->sizes;
    }

    public function setSizes(array $sizes): self
    {
        $this->sizes = $sizes;

        return $this;
    }

    public function getCredit(): ?string
    {
        return $this->credit;
    }

    public function setCredit(?string $credit): self
    {
        $this->credit = $credit;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getCopyright(): ?string
    {
        return $this->copyright;
    }

    public function setCopyright(?string $copyright): self
    {
        $this->copyright = $copyright;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getKeywords(): array
    {
        return $this->keywords;
    }

    public function setKeywords(array $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getAlternativeText(): ?string
    {
        return $this->alternativeText;
    }

    public function setAlternativeText(?string $alternativeText): self
    {
        $this->alternativeText = $alternativeText;

        return $this;
    }

    public function isImage(): bool
    {
        return str_starts_with((string)$this->getMimeType(), 'image/');
    }

    public function isVideo(): bool
    {
        return str_starts_with((string)$this->getMimeType(), 'video/');
    }

    /**
     * @return array|null Associative array with the following keys: width, height, filename, mime_type
     */
    public function getSize(string $size): ?array
    {
        return $this->sizes ? ($this->sizes[$size] ?? null) : null;
    }

    public function getSizePath(string $size): string
    {
        if (!($sizeInfo = $this->getSize($size)) || !$this->getPath()) {
            return '';
        }

        return dirname((string)$this->getPath()) . '/' . $sizeInfo['filename'];
    }

    /**
     * @return string[]
     */
    public function getAllAssociatedFilePaths(): array
    {
        $paths = [];

        foreach (array_keys($this->sizes) as $size) {
            $paths[] = $this->getSizePath($size);
        }

        return $paths;
    }
}
