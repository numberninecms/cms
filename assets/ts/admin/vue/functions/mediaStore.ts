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

interface MediaStoreOptions {
    mediaUrl: string;
}

interface MediaStore {
    mediaFiles: Ref<MediaFile[]>;
    mediaFilesFilter: Ref<string>;
    selectedMediaFiles: Ref<MediaFile[]>;
    loadMoreMediaFiles: () => void;
    isMediaFileSelected: (mediaFile: MediaFile) => boolean;
    selectMediaFile: (file: MediaFile) => void;
    clearMediaFilesSelection: () => void;
    setBulkSelectFirstIndex: (index: number) => void;
    bulkMediaSelect: (index: number) => void;
    selectMultipleMediaFiles: Ref<boolean>;
}

interface Pagination {
    startRow: number;
    fetchCount: number;
    filter?: string;
    orderBy: string;
    order: SortDirection;
    status: string;
}

export default function useMediaStore(options: MediaStoreOptions): MediaStore {
    const mediaFiles: Ref<MediaFile[]> = ref([]);
    const selectedMediaFiles: Ref<MediaFile[]> = ref([]);
    const filter = ref('');
    const selectMultiple = ref(false);
    const ROWS_PER_PAGE = 20;
    const queue: Function[] = [];
    let page = 1;
    let maxPages = 1;
    let isFetching = false;
    const selectionFirstIndex = ref(0);

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

    function isMediaFileSelected(mediaFile: MediaFile): boolean {
        return !!selectedMediaFiles.value.find((f) => f.id === mediaFile.id);
    }

    function setBulkSelectFirstIndex(index: number): void {
        if (!selectMultiple.value) {
            const isToggle = !!selectedMediaFiles.value.find((f) => f.id === mediaFiles.value[index].id);
            clearMediaFilesSelection();

            if (!isToggle) {
                selectMediaFile(mediaFiles.value[index]);
            }
        } else {
            selectMediaFile(mediaFiles.value[index]);
        }

        selectionFirstIndex.value = index;
    }

    function bulkMediaSelect(index: number): void {
        if (selectMultiple.value) {
            const files = mediaFiles.value.slice(selectionFirstIndex.value, index + 1);
            files.forEach((file) => {
                if (!selectedMediaFiles.value.find((f) => f.id === file.id)) {
                    selectedMediaFiles.value.push(file);
                }
            });
        }
    }

    function selectMediaFile(file: MediaFile): void {
        if (!selectedMediaFiles.value.find((f) => f.id === file.id)) {
            selectedMediaFiles.value.push(file);
        } else {
            selectedMediaFiles.value.splice(
                selectedMediaFiles.value.findIndex((f) => f.id === file.id),
                1,
            );
        }
    }

    function clearMediaFilesSelection(): void {
        selectedMediaFiles.value.splice(0, selectedMediaFiles.value.length);
    }

    return {
        mediaFiles,
        mediaFilesFilter: filter,
        selectedMediaFiles,
        loadMoreMediaFiles,
        isMediaFileSelected,
        selectMediaFile,
        clearMediaFilesSelection,
        setBulkSelectFirstIndex,
        bulkMediaSelect,
        selectMultipleMediaFiles: selectMultiple,
    };
}
