/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { defineStore } from 'pinia';
import PaginatedCollectionResponse from 'admin/interfaces/PaginatedCollectionResponse';
import MediaFile from 'admin/interfaces/MediaFile';
import axios from 'axios';

type SortDirection = 'asc' | 'desc';

interface Pagination {
    startRow: number;
    fetchCount: number;
    filter?: string;
    orderBy: string;
    order: SortDirection;
    status: string;
}

const ROWS_PER_PAGE = 20;

export const useMediaFilesStore = defineStore({
    id: 'mediaFiles',
    state() {
        return {
            getUrl: '',
            deleteUrl: '',
            mediaFiles: [] as MediaFile[],
            selectedMediaFiles: [] as MediaFile[],
            filter: '',
            page: 1,
            maxPages: 1,
            isFetching: false,
            queue: [] as Function[],
            selectionFirstIndex: -1,
            selectMultiple: false,
        };
    },
    actions: {
        setup({ getUrl, deleteUrl }: { getUrl: string; deleteUrl: string }) {
            this.getUrl = getUrl;
            this.deleteUrl = deleteUrl;
        },

        async loadMoreMediaFiles(): Promise<void> {
            const getMediaFiles = async (page = 1): Promise<PaginatedCollectionResponse<MediaFile>> => {
                const pagination: Pagination = {
                    startRow: (page - 1) * ROWS_PER_PAGE,
                    fetchCount: ROWS_PER_PAGE,
                    filter: this.filter,
                    orderBy: 'createdAt',
                    order: 'desc',
                    status: 'publish,private,pending_review,password,draft',
                };

                const response = await axios.get(this.getUrl, {
                    params: pagination,
                });

                return response.data as PaginatedCollectionResponse<MediaFile>;
            };

            const fetch = async () => {
                if (this.page > this.maxPages) {
                    return;
                }

                this.isFetching = true;
                const newMediaFiles = await getMediaFiles(this.page++);
                this.maxPages = newMediaFiles.page.last;
                this.mediaFiles.push(...newMediaFiles.collection);
                this.isFetching = false;

                const next = this.queue.shift();

                if (next) {
                    await next();
                }
            };

            if (this.isFetching) {
                this.queue.push(fetch);
                return;
            }

            await fetch();
        },

        async deleteMediaFiles(files: MediaFile[]): Promise<void> {
            await axios.post(this.deleteUrl, { ids: files.map((e) => e.id) });

            files.forEach((file) => {
                this.mediaFiles.splice(
                    this.mediaFiles.findIndex((f) => f.id === file.id),
                    1,
                );
            });
        },

        setBulkSelectFirstIndex(index: number): void {
            const file = this.selectedMediaFiles.find((f) => f.id === this.mediaFiles[index].id);
            const isSelected = !!file;

            if (!isSelected) {
                this.selectMediaFile(this.mediaFiles[index] as MediaFile);
            } else {
                this.selectedMediaFiles.splice(this.selectedMediaFiles.indexOf(file!), 1);
            }

            this.selectionFirstIndex = index;
        },

        bulkMediaSelect(index: number): void {
            if (this.selectMultiple && this.selectionFirstIndex !== -1) {
                const files = this.mediaFiles.slice(this.selectionFirstIndex, index + 1);
                files.forEach((file) => {
                    if (!this.selectedMediaFiles.find((f) => f.id === file.id)) {
                        this.selectedMediaFiles.push(file);
                    }
                });
            }
        },

        selectMediaFile(file: MediaFile): void {
            if (!this.selectedMediaFiles.find((f) => f.id === file.id)) {
                this.selectedMediaFiles.push(file);
            } else {
                this.selectedMediaFiles.splice(
                    this.selectedMediaFiles.findIndex((f) => f.id === file.id),
                    1,
                );
            }
        },

        clearMediaFilesSelection(): void {
            this.selectedMediaFiles.splice(0, this.selectedMediaFiles.length);
            this.selectionFirstIndex = -1;
        },

        isMediaFileSelected(mediaFile: MediaFile): boolean {
            return !!this.selectedMediaFiles.find((f) => f.id === mediaFile.id);
        },
    },
});
