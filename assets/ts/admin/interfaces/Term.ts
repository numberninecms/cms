/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import Taxonomy from 'admin/interfaces/Taxonomy';

export default interface Term {
    id?: number;
    parent?: Term;
    taxonomy?: Taxonomy;
    name: string;
    slug: string;
    description?: string;
}
