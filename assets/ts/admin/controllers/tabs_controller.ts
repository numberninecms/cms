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
    public static targets = ['tab'];

    private readonly tabTargets: HTMLAnchorElement[];

    public connect(): void {
        this.tabTargets.forEach((tab) => {
            if (window.location.pathname + window.location.search === tab.getAttribute('href')) {
                tab.classList.add('active', 'font-semibold');
            }

            tab.onclick = () => {
                window.history.pushState(null, tab.innerText, tab.getAttribute('href') as string);
            };
        });
    }
}
