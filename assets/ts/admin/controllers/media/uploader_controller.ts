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
import MediaUploader from '../../vue/components/MediaUploader.vue';

export default class extends Controller {
    public static targets = ['uploader'];

    private readonly uploaderTarget: HTMLElement;

    public connect(): void {
        createApp(MediaUploader, {
            uploadUrl: this.uploaderTarget.dataset.uploadUrl,
            maxUploadSize: this.uploaderTarget.dataset.maxUploadSize,
        }).mount('#media-uploader');
    }
}
