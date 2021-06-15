/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { defineStore } from 'pinia';

export const useMouseStore = defineStore({
    id: 'mouse',
    state() {
        return {
            x: 0,
            y: 0,
            over: false,
        };
    },
});
