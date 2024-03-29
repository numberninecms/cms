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

export default class extends Controller {
    public static targets = ['dragHandle'];
    public static values = {
        id: String,
    };

    private readonly dragHandleTargets: HTMLElement[];
    private readonly idValue: string;

    public connect(): void {
        eventBus.on(EVENT_MODAL_SHOW, (event) => {
            if (event.modalId === this.idValue) {
                this.show();
            }
        });

        eventBus.on(EVENT_MODAL_CLOSE, (event) => {
            if (event.modalId === this.idValue) {
                this.close();
            }
        });

        window.addEventListener('keydown', this.onKeyDown.bind(this));

        if (this.dragHandleTargets.length > 0) {
            this.dragHandleTargets.forEach((dragHandle) =>
                dragHandle.addEventListener('mousedown', this.dragStart.bind(this)),
            );
        }
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
        });
    }

    public close(): void {
        (this.element as HTMLElement).style.display = 'none';
        eventBus.emit(EVENT_MODAL_VISIBILITY_CHANGED, {
            element: this.element,
            visible: false,
        });
    }

    private dragStart(event: MouseEvent): void {
        event.preventDefault();
        event.stopPropagation();

        (this.element as HTMLElement).style.position = 'absolute';
        const rect = (this.element as HTMLElement).getBoundingClientRect();

        const dragOffsetX = Math.max(30, event.clientX - rect.left);
        const dragOffsetY = Math.max(30, event.clientY - rect.top);

        const dragStop = (): void => {
            window.removeEventListener('mousemove', moveModal);
            window.removeEventListener('mouseup', dragStop);
        };

        const moveModal = (event: MouseEvent): void => {
            (this.element as HTMLElement).style.left = `${event.clientX - dragOffsetX}px`;
            (this.element as HTMLElement).style.top = `${event.clientY - dragOffsetY}px`;
        };

        window.addEventListener('mousemove', moveModal);
        window.addEventListener('mouseup', dragStop);
    }
}
