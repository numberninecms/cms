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
    public static values = {
        uploadUrl: String,
        maxUploadSize: Number,
        autoUpload: Boolean,
        sequential: Boolean,
    };

    private readonly uploadUrlValue: string;
    private readonly maxUploadSizeValue: number;
    private readonly autoUploadValue: boolean;
    private readonly hasAutoUploadValue: boolean;
    private readonly sequentialValue: boolean;
    private readonly hasSequentialValue: boolean;

    public connect(): void {
        createApp(MediaUploader, {
            uploadUrl: this.uploadUrlValue,
            maxUploadSize: this.maxUploadSizeValue,
            autoUpload: this.hasAutoUploadValue ? this.autoUploadValue : undefined,
            sequential: this.hasSequentialValue ? this.sequentialValue : undefined,
        }).mount('#media-uploader');
    }
}
