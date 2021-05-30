/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import ContentEntity from 'admin/interfaces/ContentEntity';
import ImageSizes from 'admin/interfaces/ImageSizes';
import GenericObject from 'admin/interfaces/GenericObject';

export default interface MediaFile extends ContentEntity {
    width: number;
    height: number;
    duration: number;
    path: string;
    mimeType: string;
    sizes: ImageSizes;
    slug: string;
    title: string;
    fileSize: number;
    alternativeText?: string;
    caption?: string;
    copyright?: string;
    exif?: GenericObject<any>;
}
