/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

export default interface Revision {
    version: number;
    title?: string;
    slug?: string;
    content?: string;
    excerpt?: string;
    date?: string;
}
