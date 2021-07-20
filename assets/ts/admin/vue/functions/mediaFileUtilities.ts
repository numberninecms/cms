/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import MediaFile from 'admin/interfaces/MediaFile';
import path from 'path';

interface MediaFileUtilities {
    imageUrl: (mediaFile: MediaFile, size?: string) => string | null;
}

export default function useMediaFileUtilities(): MediaFileUtilities {
    function imageUrl(mediaFile: MediaFile, size = 'thumbnail'): string | null {
        return mediaFile &&
            mediaFile.mimeType.substr(0, 5) === 'image' &&
            Object.prototype.hasOwnProperty.call(mediaFile.sizes, size)
            ? `${path.dirname(mediaFile.path)}/${mediaFile.sizes[size].filename}`
            : null;
    }

    return {
        imageUrl,
    };
}
