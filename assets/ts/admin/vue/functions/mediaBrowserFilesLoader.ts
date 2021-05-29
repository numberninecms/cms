/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Ref, ref } from 'vue';
import MediaFile from 'admin/interfaces/MediaFile';
import PaginatedCollectionResponse from 'admin/interfaces/PaginatedCollectionResponse';
import axios from 'axios';

type SortDirection = 'asc' | 'desc';

interface MediaBrowserFilesLoaderOptions {
    mediaUrl: string;
}

interface MediaBrowserFilesLoader {
    mediaFiles: Ref<MediaFile[]>;
    mediaFilesFilter: Ref<string>;
    loadMoreMediaFiles: () => void;
}

interface Pagination {
    startRow: number;
    fetchCount: number;
    filter?: string;
    orderBy: string;
    order: SortDirection;
    status: string;
}

export default function useMediaBrowserFilesLoader(options: MediaBrowserFilesLoaderOptions): MediaBrowserFilesLoader {
    const mediaFiles: Ref<MediaFile[]> = ref([]);
    const filter = ref('');
    const ROWS_PER_PAGE = 20;
    const queue: Function[] = [];
    let page = 1;
    let maxPages = 1;
    let isFetching = false;

    async function getMediaFiles(page = 1): Promise<PaginatedCollectionResponse<MediaFile>> {
        const pagination: Pagination = {
            startRow: (page - 1) * ROWS_PER_PAGE,
            fetchCount: ROWS_PER_PAGE,
            filter: filter.value,
            orderBy: 'createdAt',
            order: 'desc',
            status: 'publish,private,pending_review,password,draft',
        };

        const response = await axios.get(options.mediaUrl, {
            params: pagination,
        });

        return response.data as PaginatedCollectionResponse<MediaFile>;
    }

    function loadMoreMediaFiles(): void {
        async function fetch() {
            if (page > maxPages) {
                return;
            }

            isFetching = true;
            const newMediaFiles = await getMediaFiles(page++);
            maxPages = newMediaFiles.page.last;
            mediaFiles.value.push(...newMediaFiles.collection);
            isFetching = false;

            const next = queue.shift();

            if (next) {
                next();
            }
        }

        if (isFetching) {
            queue.push(fetch);
            return;
        }

        void fetch();
    }

    return {
        mediaFiles,
        mediaFilesFilter: filter,
        loadMoreMediaFiles,
    };
}
