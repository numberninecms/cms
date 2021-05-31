/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import GenericObject from 'admin/interfaces/GenericObject';
import KeyValueEntity from 'admin/interfaces/KeyValueEntity';
import MediaFile from 'admin/interfaces/MediaFile';
import Term from 'admin/interfaces/Term';
import User from 'admin/interfaces/User';

export default interface ContentEntity {
    id: number;
    type: string;
    title: string;
    slug: string;
    author: User;
    publicUrl: string;
    createdAt: Date;
    status: string;
    customFields?: GenericObject<any>;
    customFieldsComputed: KeyValueEntity<any>[];
    featuredImage?: MediaFile;
    seoTitle?: string;
    seoDescription?: string;
    content?: string;
    excerpt?: string;
    terms?: Term[];
}
