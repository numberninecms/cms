/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { defineStore } from 'pinia';
import Color from 'admin/interfaces/Color';
import axios from 'axios';

export const useColorStore = defineStore({
    id: 'color',
    state() {
        return {
            colors: [] as Color[],
        };
    },
    actions: {
        async setup(url: string): Promise<void> {
            const response = await axios.get(url);
            this.colors = response.data;
        },
    },
});
