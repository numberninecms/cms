/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { defineStore } from 'pinia';
import Menu from 'admin/interfaces/Menu';
import axios from 'axios';
import Routing from 'assets/ts/routing';

export const useMenuStore = defineStore({
    id: 'menu',
    state() {
        return {
            menus: [] as Menu[],
        };
    },
    actions: {
        async fetchMenus(): Promise<void> {
            if (this.menus.length === 0) {
                const response = await axios.get(Routing.generate('numbernine_admin_menus_get_collection'));
                this.menus = response.data;
            }
        },
    },
});
