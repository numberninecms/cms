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
import MediaFile from 'admin/interfaces/MediaFile';
import { EVENT_MEDIA_SELECT, EVENT_MODAL_SHOW } from 'admin/events/events';
import { dirname } from 'path';

export default class extends Controller {
    public static targets = ['image', 'text', 'input', 'remove'];

    private readonly imageTarget: HTMLElement;
    private readonly textTarget: HTMLElement;
    private readonly inputTarget: HTMLInputElement;
    private readonly removeTarget: HTMLElement;

    public select(): void {
        EventBus.emit(EVENT_MODAL_SHOW, 'media_library');
        EventBus.emit(EVENT_MEDIA_SELECT, ({ files }: { files: MediaFile[] }) => {
            if (files.length > 0) {
                const img = document.createElement('img');
                const size = Object.prototype.hasOwnProperty.call(files[0].sizes, 'preview') ? 'preview' : 'original';
                img.src = `${dirname(files[0].path)}/${files[0].sizes[size].filename}`;
                this.imageTarget.innerHTML = img.outerHTML;
                this.textTarget.style.display = 'none';
                this.inputTarget.value = `${files[0].id}`;
                this.removeTarget.style.display = 'inline';
            }
        });
    }

    public remove(): void {
        this.imageTarget.innerHTML = '';
        this.textTarget.style.display = 'block';
        this.inputTarget.value = '';
        this.removeTarget.style.display = 'none';
    }
}