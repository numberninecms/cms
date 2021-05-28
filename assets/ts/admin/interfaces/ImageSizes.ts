/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

export default interface ImageSizes {
    [name: string]: {
        width: number;
        height: number;
        filename: string;
        mimeType: string;
    };
}
