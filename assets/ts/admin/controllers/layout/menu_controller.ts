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
    public static targets = ['menuItem'];

    private readonly menuItemTargets: HTMLAnchorElement[];

    public connect(): void {
        console.log(window.location.pathname);
        this.menuItemTargets.forEach((menuItem) => {
            console.log(menuItem.getAttribute('href'));
            if (window.location.pathname === menuItem.getAttribute('href')) {
                menuItem.classList.add('active');

                const div = this.getParentExpandableDiv(menuItem);

                if (div) {
                    div.style.display = 'block';
                }
            }
        });
    }

    private getParentExpandableDiv(menuItem: HTMLAnchorElement): HTMLDivElement | null {
        const expandable = menuItem.closest('.expandable');

        if (expandable) {
            const div = expandable.querySelector(':scope > div') as HTMLDivElement;

            if (div) {
                return div;
            }
        }

        return null;
    }
}
