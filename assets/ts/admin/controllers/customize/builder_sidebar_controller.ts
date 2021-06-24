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
import PageBuilderComponentsTree from 'admin/vue/components/builder/PageBuilderComponentsTree.vue';

export default class extends Controller {
    public static targets = ['content'];

    private readonly contentTarget: HTMLElement;

    public connect(): void {
        createApp(PageBuilderComponentsTree).mount(this.contentTarget);
    }
}
