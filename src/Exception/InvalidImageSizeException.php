<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Exception;

use Exception;
use NumberNine\Entity\MediaFile;
use NumberNine\Model\Customizer\Control\ControlInterface;

final class InvalidImageSizeException extends Exception
{
    /**
     * @param MediaFile $mediaFile
     * @param string $size
     */
    public function __construct(MediaFile $mediaFile, string $size)
    {
        parent::__construct(sprintf('MediaFile entity with ID "%d" has no size named "%s". Make sure this size is configured properly, then regenerate thumbnails.', $mediaFile->getId(), $size));
    }
}
