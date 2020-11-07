<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\ImageShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode;
use NumberNine\Annotation\Shortcode\Exclude;
use NumberNine\Entity\MediaFile;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\CacheableContent;
use NumberNine\Repository\MediaFileRepository;

use function NumberNine\Common\Util\ArrayUtil\array_implode_associative;
use function NumberNine\Common\Util\ArrayUtil\array_set_if_value_exists;

/**
 * @Shortcode(name="image", label="Image", editable=true, icon="image")
 * @Shortcode\ExclusionPolicy("none")
 */
final class ImageShortcode extends AbstractShortcode implements CacheableContent
{
    private MediaFileRepository $mediaFileRepository;

    /**
     * @Control\Image(label="Image")
     */
    private ?int $id = null;

    private ?string $fromTitle = null;

    /**
     * @Exclude("serialization")
     */
    private ?MediaFile $image = null;

    /**
     * @Exclude("serialization")
     */
    private ?string $uploadPath = null;

    /**
     * @Control\SliderInput(label="Maximum width", max=1000, suffix="px")
     */
    private int $maxWidth = 400;

    /**
     * @Control\SliderInput(label="Maximum height", max=1000.0, suffix="px")
     */
    private int $maxHeight = 200;

    public function __construct(MediaFileRepository $mediaFileRepository, string $uploadPath)
    {
        $this->mediaFileRepository = $mediaFileRepository;
        $this->uploadPath = $uploadPath;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getFromTitle(): ?string
    {
        return $this->fromTitle;
    }

    public function setFromTitle(?string $fromTitle): void
    {
        if ($this->id) {
            $this->fromTitle = null;
            return;
        }

        $this->fromTitle = $fromTitle;
    }

    public function getImage(): ?MediaFile
    {
        if (!$this->id && !$this->fromTitle) {
            return null;
        }

        if (!$this->image) {
            if ($this->id) {
                $this->image = $this->mediaFileRepository->find($this->id);
            } else {
                $this->image = $this->mediaFileRepository->findOneBy(['title' => $this->fromTitle]);
            }
        }

        return $this->image;
    }

    public function getUploadPath(): ?string
    {
        return $this->uploadPath;
    }

    /**
     * @Exclude("serialization")
     */
    public function getStyles(): string
    {
        $styles = [];

        array_set_if_value_exists($styles, 'max-width', $this->maxWidth, sprintf('%dpx', $this->maxWidth));
        array_set_if_value_exists($styles, 'max-height', $this->maxHeight, sprintf('%dpx', $this->maxHeight));

        return array_implode_associative($styles, ';', ':');
    }

    public function getMaxWidth(): int
    {
        return $this->maxWidth;
    }

    public function setMaxWidth(int $maxWidth): void
    {
        $this->maxWidth = $maxWidth;
    }

    public function getMaxHeight(): int
    {
        return $this->maxHeight;
    }

    public function setMaxHeight(int $maxHeight): void
    {
        $this->maxHeight = $maxHeight;
    }

    /**
     * @Shortcode\Exclude
     */
    public function getCacheIdentifier(): string
    {
        return sprintf('shortcode_image_%s_%s_%d_%d', $this->id, $this->fromTitle, $this->maxWidth, $this->maxHeight);
    }
}
