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

final class InvalidMimeTypeException extends Exception
{
    /**
     * @param MediaFile $mediaFile
     * @param string $expectedMimeType
     */
    public function __construct(MediaFile $mediaFile, string $expectedMimeType)
    {
        parent::__construct(sprintf(
            'MediaFile entity with ID "%d" should be of type %s.',
            $mediaFile->getId(),
            $expectedMimeType
        ));
    }
}
