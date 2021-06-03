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
import { ObserveVisibility } from 'vue-observe-visibility';
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
            .directive('observe-visibility', {
                beforeMount: (el, binding, vnode) => {
                    // eslint-disable-next-line @typescript-eslint/no-unsafe-member-access
                    (vnode as any).context = binding.instance;
                    // eslint-disable-next-line @typescript-eslint/no-unsafe-member-access
                    ObserveVisibility.bind(el, binding, vnode);
                },
                // eslint-disable-next-line @typescript-eslint/no-unsafe-member-access
                updated: ObserveVisibility.update,
                // eslint-disable-next-line @typescript-eslint/no-unsafe-member-access
                unmounted: ObserveVisibility.unbind,
            })
            .mount('#media-browser');
    }
}
