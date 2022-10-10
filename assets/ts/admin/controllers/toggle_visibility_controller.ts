/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    public static targets = ['item'];

    private readonly itemTargets: HTMLElement[];

    public toggle(event: Event): void {
        event.preventDefault();
        this.itemTargets.forEach((item) => {
            item.style.display = item.style.display !== 'block' ? 'block' : 'none';
        });
    }
}
