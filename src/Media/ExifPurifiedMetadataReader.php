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

use Exception;
use Imagine\File\Loader;
use Imagine\File\LoaderInterface;
use Imagine\Image\Metadata\ExifMetadataReader;
use Imagine\Utils\ErrorHandling;
use Throwable;

final class ExifPurifiedMetadataReader extends ExifMetadataReader
{
    /**
     * {@inheritdoc}
     *
     * @see \Imagine\Image\Metadata\AbstractMetadataReader::extractFromFile()
     */
    protected function extractFromFile($file): array
    {
        $loader = $file instanceof LoaderInterface ? $file : new Loader($file);

        return $this->doReadData($loader->getData());
    }

    /**
     * Extracts metadata from raw data, merges with existing metadata.
     *
     * @param string $data
     */
    private function doReadData($data): array
    {
        if (str_starts_with($data, 'II')) {
            $mime = 'image/tiff';
        } else {
            $mime = 'image/jpeg';
        }

        return $this->extract('data://' . $mime . ';base64,' . base64_encode($data));
    }

    /**
     * Performs the exif data extraction given a path or data-URI representation.
     *
     * @param string $path the path to the file or the data-URI representation
     */
    protected function extract($path): array
    {
        try {
            $exifData = ErrorHandling::ignoring(
                -1,
                static function () use ($path): array|bool {
                    return @exif_read_data($path, '', true, false);
                }
            );
        } catch (Exception) {
            $exifData = false;
        } catch (Throwable) {
            $exifData = false;
        }
        if (!is_array($exifData)) {
            return array();
        }

        $metadata = array();
        foreach ($exifData as $prefix => $values) {
            if (is_array($values)) {
                $prefix = strtolower($prefix);
                foreach ($values as $prop => $value) {
                    $metadata[$prefix . '.' . $prop] = preg_replace('/[\x00-\x1F\x7F]/u', '', $value);
                }
            }
        }

        return $metadata;
    }
}
