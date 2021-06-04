/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

interface StringHelpers {
    readableBytes: (bytes: number) => string;
    upperFirst: (text: string) => string;
}

export default function useStringHelpers(): StringHelpers {
    function readableBytes(bytes: number): string {
        const i: number = Math.floor(Math.log(bytes) / Math.log(1024));
        const sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        return `${(bytes / Math.pow(1024, i)).toFixed(2) as any as number} ${sizes[i]}`;
    }

    function upperFirst(text: string): string {
        return text ? text.charAt(0).toUpperCase() + text.slice(1) : '';
    }

    return {
        readableBytes,
        upperFirst,
    };
}
