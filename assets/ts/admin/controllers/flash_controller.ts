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
import { EVENT_FLASH_SHOW } from 'admin/events/events';
import FlashShowEvent from 'admin/events/FlashShowEvent';

export default class extends Controller {
    public static targets = ['message'];

    private readonly messageTarget: HTMLElement;

    public connect(): void {
        eventBus.on(EVENT_FLASH_SHOW, this.showFlash.bind(this));
    }

    private showFlash(event: FlashShowEvent): void {
        this.element.classList.remove('flash-', 'flash-success', 'flash-error', 'flash-warning');
        this.element.classList.add(`flash-${event.label as string}`);
        (this.element as HTMLElement).style.display = 'block';
        this.messageTarget.innerHTML = event.message;
    }
}
