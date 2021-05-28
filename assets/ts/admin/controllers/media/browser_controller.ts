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
import MediaBrowser from '../../vue/components/MediaBrowser.vue';

export default class extends Controller {
    public static values = {
        mediaUrl: String,
    };

    private readonly mediaUrlValue: string;

    public connect(): void {
        createApp(MediaBrowser, {
            mediaUrl: this.mediaUrlValue,
        }).mount('#media-browser');
    }
}
