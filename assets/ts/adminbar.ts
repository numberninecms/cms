/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import '../scss/adminbar.scss';

class MenuBurgerButton {
    private readonly DOMElement: HTMLElement | null;
    private showMenu = false;

    public constructor(selector: string) {
        this.DOMElement = document.querySelector(selector);

        if (this.DOMElement) {
            this.handle();
        }
    }

    private handle() {
        this.DOMElement!.addEventListener('click', (e) => {
            e.preventDefault();
            this.showMenu = !this.showMenu;

            const menu: HTMLElement | null = document.querySelector('.n9-topbar-menu');

            if (menu) {
                menu.style.display = this.showMenu ? 'block' : 'none';
            }
        });
    }
}

new MenuBurgerButton('.n9-topbar-burger');
