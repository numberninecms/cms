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
        contentEntitySingleUrl: String,
    };

    private readonly colorsUrlValue: string;
    private readonly contentEntitySingleUrlValue: string;

    public connect(): void {
        createApp(PageBuilderComponentForm, {
            colorsUrl: this.colorsUrlValue,
            contentEntitySingleUrlValue: this.contentEntitySingleUrlValue,
        })
            .use(createPinia())
            .mount(this.element);
    }
}
