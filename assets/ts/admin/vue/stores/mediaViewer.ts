/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { defineStore } from 'pinia';
import MediaFile from 'admin/interfaces/MediaFile';
import MediaSettings from 'admin/interfaces/MediaSettings';
import { useMediaFilesStore } from 'admin/vue/stores/mediaFiles';

export const useMediaViewerStore = defineStore({
    id: 'mediaViewer',
    state() {
        return {
            displayIndex: -1,
            show: false,
            callback: Object as ({ files, settings }: { files: MediaFile[]; settings: MediaSettings }) => void,
            settings: {} as MediaSettings,
        };
    },
    actions: {
        isMediaFileDisplayed(mediaFile: MediaFile) {
            const mediaFilesStore = useMediaFilesStore();
            return mediaFilesStore.mediaFiles.findIndex((f) => f.id === mediaFile.id) === this.displayIndex;
        },
    },
});
