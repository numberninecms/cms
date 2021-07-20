/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import ParsedFile from 'admin/interfaces/ParsedFile';
import MediaFile from 'admin/interfaces/MediaFile';

export default interface MediaFileUploadedEvent {
    parsedFile: ParsedFile;
    mediaFile: MediaFile;
    remainingCount: number;
}
