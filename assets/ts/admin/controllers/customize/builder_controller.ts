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

export default class extends Controller {
    public static values = {
        url: String,
    };

    private readonly urlValue: string;

    public connect(): void {
        window.addEventListener('resize', () => this.emitWindowHeight());
        this.emitWindowHeight();

        const app = createApp(PageBuilderFrame, {
            url: this.urlValue,
            disableLinks: true,
        });

        app.mount(this.element);
    }

    private emitWindowHeight(): void {
        eventBus.emit(EVENT_PAGE_BUILDER_FRAME_HEIGHT_CHANGED, document.documentElement.clientHeight);
    }
}
