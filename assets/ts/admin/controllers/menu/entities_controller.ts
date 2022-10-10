/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from '@hotwired/stimulus';
import { eventBus } from 'admin/admin';
import { EVENT_MENU_ADD_ITEMS } from 'admin/events/events';
import { v4 as uuidv4 } from 'uuid';
import MenuItem from 'admin/interfaces/MenuItem';

export default class extends Controller {
    public static targets = ['entity'];

    private readonly entityTargets: HTMLInputElement[];

    public addToMenu(): void {
        const checkedEntities = this.entityTargets.filter((e) => e.checked);

        eventBus.emit(EVENT_MENU_ADD_ITEMS, {
            items: checkedEntities.map((e) => {
                return {
                    uid: uuidv4(),
                    entityId: e.dataset.id ? parseInt(e.dataset.id) : undefined,
                    title: e.dataset.title,
                    icon: e.dataset.icon,
                    children: [],
                } as MenuItem;
            }),
        });
    }
}
