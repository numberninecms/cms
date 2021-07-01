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

export const useContentEntityStore = defineStore({
    id: 'contentEntity',
    state() {
        return {
            fetchSingleEntityUrl: '',
        };
    },
    actions: {
        setup({ fetchSingleEntityUrl }: { fetchSingleEntityUrl: string }) {
            this.fetchSingleEntityUrl = fetchSingleEntityUrl;
        },

        async fetchSingleEntityById<T extends ContentEntity>(id: number): Promise<T> {
            const response = await axios.get(
                this.fetchSingleEntityUrl.replace('__type__', 'media_file').replace('__id__', id.toString()),
            );

            return response.data as T;
        },
    },
});
