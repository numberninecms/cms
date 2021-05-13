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
    public static targets = ['itemContainer', 'item', 'blogAsHomepage'];

    private readonly itemContainerTargets: HTMLElement[];
    private readonly itemTargets: HTMLSelectElement[];
    private readonly blogAsHomepageTarget: HTMLInputElement;

    public toggleBlogAsHomepage(): void {
        if (this.blogAsHomepageTarget.checked) {
            this.itemTargets.forEach((item) => {
                item.value = '';
            });

            this.itemContainerTargets.forEach((itemContainer) => {
                itemContainer.style.display = 'none';
            });
        } else {
            this.itemContainerTargets.forEach((itemContainer) => {
                itemContainer.style.display = 'flex';
            });
        }
    }
}
