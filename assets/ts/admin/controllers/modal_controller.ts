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
import { EVENT_MODAL_CLOSE, EVENT_MODAL_SHOW, EVENT_MODAL_VISIBILITY_CHANGED } from 'admin/events/events';
import ModalVisibilityChangedEvent from 'admin/events/ModalVisibilityChangedEvent';

export default class extends Controller {
    public static values = {
        id: String,
    };

    private readonly idValue: string;

    public connect(): void {
        eventBus.on(EVENT_MODAL_SHOW, (id) => {
            if (id === this.idValue) {
                this.show();
            }
        });

        eventBus.on(EVENT_MODAL_CLOSE, (id) => {
            if (id === this.idValue) {
                this.close();
            }
        });

        window.addEventListener('keydown', this.onKeyDown.bind(this));
    }

    public disconnect(): void {
        window.removeEventListener('keydown', this.onKeyDown.bind(this));
    }

    private onKeyDown(event: KeyboardEvent): void {
        if (event.key === 'Escape') {
            this.close();
        }
    }

    public show(): void {
        (this.element as HTMLElement).style.display = 'flex';
        eventBus.emit(EVENT_MODAL_VISIBILITY_CHANGED, {
            element: this.element,
            visible: true,
        } as ModalVisibilityChangedEvent);
    }

    public close(): void {
        (this.element as HTMLElement).style.display = 'none';
        eventBus.emit(EVENT_MODAL_VISIBILITY_CHANGED, {
            element: this.element,
            visible: false,
        } as ModalVisibilityChangedEvent);
    }
}
