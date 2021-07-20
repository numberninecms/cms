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
import PageBuilderComponentForm from 'admin/vue/components/builder/PageBuilderComponentForm.vue';
import { createPinia } from 'pinia';

export default class extends Controller {
    public static values = {
        colorsUrl: String,
        frontendCssUrl: String,
    };

    private readonly colorsUrlValue: string;
    private readonly frontendCssUrlValue: string;

    public connect(): void {
        createApp(PageBuilderComponentForm, {
            colorsUrl: this.colorsUrlValue,
            frontendCssUrl: this.frontendCssUrlValue,
        })
            .use(createPinia())
            .mount(this.element);
    }
}
