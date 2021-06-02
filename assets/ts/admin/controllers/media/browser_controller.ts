/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from 'stimulus';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import MediaBrowser from '../../vue/components/MediaBrowser.vue';

export default class extends Controller {
    public static values = {
        getUrl: String,
        deleteUrl: String,
        mode: String,
    };

    private readonly getUrlValue: string;
    private readonly deleteUrlValue: string;
    private readonly modeValue: string;

    public connect(): void {
        createApp(MediaBrowser, {
            getUrl: this.getUrlValue,
            deleteUrl: this.deleteUrlValue,
            mode: this.modeValue,
        })
            .use(createPinia())
            .mount('#media-browser');
    }
}
