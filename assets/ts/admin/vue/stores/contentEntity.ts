/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { defineStore } from 'pinia';
import ContentEntity from 'admin/interfaces/ContentEntity';
import axios from 'axios';
import Routing from 'assets/ts/routing';

export const useContentEntityStore = defineStore({
    id: 'contentEntity',
    actions: {
        async fetchSingleEntityById<T extends ContentEntity>(id: number, type?: string): Promise<T> {
            const response = await axios.get(
                Routing.generate('numbernine_admin_contententity_get_item', {
                    id: id.toString(),
                    type: type ?? 'media_file',
                }),
            );

            return response.data as T;
        },

        async fetchSingleEntityByTitle<T extends ContentEntity>(title: string, type?: string): Promise<T> {
            const response = await axios.get(
                Routing.generate('numbernine_admin_contententity_get_item_by_title', {
                    title,
                    type: type ?? 'media_file',
                }),
            );

            return response.data as T;
        },
    },
});
