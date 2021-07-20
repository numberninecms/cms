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
        async fetchSingleEntity<T extends ContentEntity>(
            value: string,
            entity_type?: string,
            findBy?: string,
        ): Promise<T> {
            const response = await axios.get(
                Routing.generate(
                    `numbernine_admin_contententity_get_item${findBy && findBy !== 'id' ? `_by_${findBy}` : ''}`,
                    {
                        [findBy ?? 'id']: value,
                        type: entity_type ?? 'media_file',
                    },
                ),
            );

            return response.data as T;
        },
    },
});
