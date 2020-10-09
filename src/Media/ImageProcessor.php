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

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use NumberNine\Model\Media\ImageSize;
use NumberNine\Model\Media\ProcessedImage;
use Symfony\Component\Mime\MimeTypes;

final class ImageProcessor
{
    private Imagine $imagine;
    private MimeTypes $mimeTypes;

    /**
     * @param Imagine $imagine
     */
    public function __construct(Imagine $imagine)
    {
        $this->imagine = $imagine;
        $this->mimeTypes = new MimeTypes();
    }

    /**
     * @param string $filename
     * @param ImageSize[] $imageSizes
     * @return ProcessedImage
     */
    public function processImage(string $filename, array $imageSizes): ProcessedImage
    {
        $mimeType = $this->mimeTypes->guessMimeType($filename);
        $image = $this->imagine->setMetadataReader(new ExifPurifiedMetadataReader())->open($filename);

        if ($this->fixOrientation($image)) {
            $image->save();
        }

        $sizesInfo = [];

        foreach ($imageSizes as $imageSizeName => $imageSize) {
            $thumbnailData = $this->createImageVariation($filename, $imageSize);
            $thumbnailData['mime_type'] = $mimeType;
            $sizesInfo[$imageSizeName] = $thumbnailData;
        }

        $exif = $image->metadata()->toArray();

        if (isset($exif['uri'])) {
            unset($exif['uri']);
        }

        if (isset($exif['filepath'])) {
            unset($exif['filepath']);
        }

        $exif['ifd0.Orientation'] = 0;
        $exif['thumbnail.Orientation'] = 0;

        return new ProcessedImage($image, $sizesInfo, $exif);
    }

    /**
     * @param string $filename
     * @param ImageSize $size
     * @return array
     */
    public function createImageVariation(string $filename, ImageSize $size): array
    {
        $image = $this->imagine->open($filename);
        $thumbnail = $image->thumbnail(new Box((int)$size->getWidth(), (int)$size->getHeight()), $size->isCrop() ? ImageInterface::THUMBNAIL_OUTBOUND : ImageInterface::THUMBNAIL_INSET);

        $thumbnailSize = $thumbnail->getSize();
        $thumbnailFilename = sprintf('%s.%dx%d.%s', $filename, $thumbnailSize->getWidth(), $thumbnailSize->getHeight(), pathinfo($filename, PATHINFO_EXTENSION));
        $thumbnail->save($thumbnailFilename);

        return [
            'filename' => pathinfo($thumbnailFilename, PATHINFO_BASENAME),
            'width' => $thumbnailSize->getWidth(),
            'height' => $thumbnailSize->getHeight()
        ];
    }

    /**
     * Fix orientation according to Exif data
     * @param ImageInterface $image
     * @return bool
     */
    private function fixOrientation(ImageInterface $image): bool
    {
        $orientation = $image->metadata()->toArray()['ifd0.Orientation'] ?? null;

        if ($orientation === null || (int)$orientation === 0) {
            return false;
        }

        switch ($orientation) {
            case 3:
                $image->rotate(180);
                break;
            case 6:
                $image->rotate(90);
                break;
            case 9:
                $image->rotate(-90);
                break;
        }

        return true;
    }
}
