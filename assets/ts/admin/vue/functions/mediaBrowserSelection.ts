/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { ref, Ref } from 'vue';
import MediaFile from 'admin/interfaces/MediaFile';

interface MediaBrowserSelection {
    selectedMediaFiles: Ref<MediaFile[]>;
    isMediaFileSelected: (mediaFile: MediaFile) => boolean;
    selectMediaFile: (file: MediaFile) => void;
    clearMediaFilesSelection: () => void;
    setBulkSelectFirstIndex: (index: number) => void;
    bulkMediaSelect: (index: number) => void;
    selectMultipleMediaFiles: Ref<boolean>;
}

interface MediaBrowserSelectionOptions {
    mediaFiles: Ref<MediaFile[]>;
}

export default function useMediaBrowserSelection(options: MediaBrowserSelectionOptions): MediaBrowserSelection {
    const selectedMediaFiles: Ref<MediaFile[]> = ref([]);
    const selectionFirstIndex = ref(-1);
    const selectMultiple = ref(false);

    function isMediaFileSelected(mediaFile: MediaFile): boolean {
        return !!selectedMediaFiles.value.find((f) => f.id === mediaFile.id);
    }

    function setBulkSelectFirstIndex(index: number): void {
        const file = selectedMediaFiles.value.find((f) => f.id === options.mediaFiles.value[index].id);
        const isSelected = !!file;

        if (!isSelected) {
            selectMediaFile(options.mediaFiles.value[index]);
        } else {
            selectedMediaFiles.value.splice(selectedMediaFiles.value.indexOf(file!), 1);
        }

        selectionFirstIndex.value = index;
    }

    function bulkMediaSelect(index: number): void {
        if (selectMultiple.value && selectionFirstIndex.value !== -1) {
            const files = options.mediaFiles.value.slice(selectionFirstIndex.value, index + 1);
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
        selectionFirstIndex.value = -1;
    }

    return {
        selectedMediaFiles,
        isMediaFileSelected,
        selectMediaFile,
        clearMediaFilesSelection,
        setBulkSelectFirstIndex,
        bulkMediaSelect,
        selectMultipleMediaFiles: selectMultiple,
    };
}
