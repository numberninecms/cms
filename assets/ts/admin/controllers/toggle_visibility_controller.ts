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
    public static classes = ['visible'];

    private readonly itemTargets: HTMLElement[];

    public toggle(): void {
        this.itemTargets.forEach((item) => {
            item.style.display = item.style.display !== 'block' ? 'block' : 'none';
        });
    }
}
