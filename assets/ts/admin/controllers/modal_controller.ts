/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from 'stimulus';
import { EventBus } from 'admin/admin';
import { EVENT_MODAL_VISIBILITY_CHANGED, EVENT_TINY_EDITOR_SHOW_MEDIA_LIBRARY } from 'admin/events/events';
import ModalVisibilityChangedEvent from 'admin/events/ModalVisibilityChangedEvent';

export default class extends Controller {
    public connect(): void {
        EventBus.on(EVENT_TINY_EDITOR_SHOW_MEDIA_LIBRARY, () => {
            this.show();
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
        EventBus.emit(EVENT_MODAL_VISIBILITY_CHANGED, {
            element: this.element,
            visible: true,
        } as ModalVisibilityChangedEvent);
    }

    public close(): void {
        (this.element as HTMLElement).style.display = 'none';
        EventBus.emit(EVENT_MODAL_VISIBILITY_CHANGED, {
            element: this.element,
            visible: false,
        } as ModalVisibilityChangedEvent);
    }
}
