/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { defineStore } from 'pinia';

export const useMediaViewerStore = defineStore({
    id: 'mediaViewer',
    state() {
        return {
            displayIndex: -1,
            show: false,
        };
    },
});
