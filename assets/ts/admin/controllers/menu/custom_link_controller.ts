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

export default class extends Controller {
    public static targets = ['title', 'url'];

    private readonly titleTarget: HTMLInputElement;
    private readonly urlTarget: HTMLInputElement;

    public addToMenu(): void {
        eventBus.emit(EVENT_MENU_ADD_ITEMS, {
            items: [
                {
                    uid: uuidv4(),
                    title: this.titleTarget.value,
                    url: this.urlTarget.value,
                    icon: 'link',
                    children: [],
                },
            ],
        });

        this.titleTarget.value = '';
        this.urlTarget.value = '';
    }
}
