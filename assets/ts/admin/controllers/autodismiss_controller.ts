/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from 'stimulus';

export default class extends Controller {
    public static targets = ['item'];

    private readonly itemTargets: HTMLElement[];

    public connect(): void {
        setTimeout(() => {
            this.itemTargets.forEach((item) => {
                item.parentNode && item.parentNode.removeChild(item);
            });
        }, 3000);
    }
}
