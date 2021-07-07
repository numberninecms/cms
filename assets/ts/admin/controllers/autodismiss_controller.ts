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

export default class extends Controller {
    private timeout: ReturnType<typeof setTimeout>;

    public connect(): void {
        eventBus.on(EVENT_FLASH_SHOW, this.dismissAfterTimeout.bind(this));
        this.dismissAfterTimeout();
    }

    private dismissAfterTimeout(): void {
        clearTimeout(this.timeout);

        this.timeout = setTimeout(() => {
            (this.element as HTMLElement).style.display = 'none';
        }, 3000);
    }
}
