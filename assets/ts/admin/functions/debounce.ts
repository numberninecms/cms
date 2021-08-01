/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

export default function debounce<F extends (...params: any[]) => void>(fn: F, delay: number): F {
    let timeout: ReturnType<typeof setTimeout>;

    return function (this: any, ...args: any[]): void {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn.apply(this, args), delay);
    } as F;
}
