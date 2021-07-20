/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import MediaFile from 'admin/interfaces/MediaFile';

export default interface MediaLibraryThumbnailClickedEvent {
    index: number;
    file: MediaFile;
}
