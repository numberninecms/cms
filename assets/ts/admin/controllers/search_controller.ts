/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from 'stimulus';
import { FrameElement } from '@hotwired/turbo/dist/types/elements';
import debounce from 'admin/functions/debounce';

export default class extends Controller {
    public static targets = ['input', 'frame'];

    private readonly inputTarget: HTMLInputElement;
    private readonly frameTarget: FrameElement;

    private params: { [key: string]: string };

    private reloadFrame = debounce(() => {
        this.params.filter = this.inputTarget.value;
        this.frameTarget.src = `${window.location.pathname}?${new URLSearchParams(this.params).toString()}`;
    }, 300);

    public connect(): void {
        this.assignListener();
        this.inputTarget.focus();
    }

    public reset(): void {
        const oldValue = this.inputTarget.value;
        this.assignListener();
        this.inputTarget.focus();

        if (oldValue !== this.inputTarget.value) {
            this.reloadFrame();
        }
    }

    private assignListener(): void {
        const urlSearchParams = new URLSearchParams(window.location.search);
        this.params = Object.fromEntries(urlSearchParams.entries());
        this.inputTarget.value = '';

        this.inputTarget.removeEventListener('input', this.reloadFrame);
        this.inputTarget.addEventListener('input', this.reloadFrame);
    }
}
