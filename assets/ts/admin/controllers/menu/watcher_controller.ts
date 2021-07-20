/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from 'stimulus';
import { eventBus } from 'admin/admin';
import { EVENT_MENU_ITEMS_UPDATED } from 'admin/events/events';

export default class extends Controller {
    public static targets = ['menuItems'];

    private readonly menuItemsTarget: HTMLInputElement;

    public connect(): void {
        eventBus.on(EVENT_MENU_ITEMS_UPDATED, (event) => {
            this.menuItemsTarget.value = JSON.stringify(event.items);
        });
    }
}
