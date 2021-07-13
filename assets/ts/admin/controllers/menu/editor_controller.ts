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
import MenuEditor from 'admin/vue/components/menu/MenuEditor.vue';

export default class extends Controller {
    public static values = {
        items: String,
    };

    private readonly itemsValue: string;

    public connect(): void {
        createApp(MenuEditor, {
            items: JSON.parse(this.itemsValue),
        }).mount(this.element);
    }
}
