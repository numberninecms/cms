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
import PageBuilderFrame from 'admin/vue/components/builder/PageBuilderFrame.vue';
import { eventBus } from 'admin/admin';
import { EVENT_PAGE_BUILDER_FRAME_HEIGHT_CHANGED } from 'admin/events/events';
import { createPinia } from 'pinia';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    public static values = {
        frontendUrl: String,
        componentsApiUrl: String,
    };

    private readonly frontendUrlValue: string;
    private readonly componentsApiUrlValue: string;

    public connect(): void {
        window.addEventListener('resize', () => this.emitWindowHeight());
        this.emitWindowHeight();

        createApp(PageBuilderFrame, {
            frontendUrl: this.frontendUrlValue,
            componentsApiUrl: this.componentsApiUrlValue,
            disableLinks: true,
        })
            .use(createPinia())
            .mount(this.element);
    }

    private emitWindowHeight(): void {
        eventBus.emit(EVENT_PAGE_BUILDER_FRAME_HEIGHT_CHANGED, document.documentElement.clientHeight);
    }
}
