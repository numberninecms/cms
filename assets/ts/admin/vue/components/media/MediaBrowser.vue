<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div v-if="mode === 'extended'" class="flex flex-col h-full">
        <FlashBar class="sticky top-ui-area" />
        <div class="flex flex-col p-3">
            <MediaThumbnailsSelectionBar />
            <MediaThumbnailsList @thumbnail-clicked="onExtendedModeThumbnailClicked" />
        </div>
        <MediaViewer />
    </div>
    <div v-else :class="{ 'grid grid-cols-4': displayIndex !== -1 }" class="flex h-full">
        <MediaThumbnailsList
            :class="{ 'col-span-3': displayIndex !== -1 }"
            class="p-1 overflow-y-auto"
            @thumbnail-clicked="onMininmalModeThumbnailClicked"
        />
        <div v-if="displayIndex !== -1" class="col-span-1 flex flex-col gap-3 overflow-y-auto">
            <div class="flex-grow overflow-y-auto">
                <MediaFileProperties show-thumbnail />
                <MediaFileSettings />
            </div>
            <div class="flex justify-end">
                <button class="btn btn-color-primary" @click="selectFile">Select file</button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted } from 'vue';
import FlashBar from 'admin/vue/components/FlashBar.vue';
import MediaViewer from 'admin/vue/components/media/MediaViewer.vue';
import MediaThumbnailsList from 'admin/vue/components/media/MediaThumbnailsList.vue';
import MediaThumbnailsSelectionBar from 'admin/vue/components/media/MediaThumbnailsSelectionBar.vue';
import { useMediaFilesStore } from 'admin/vue/stores/mediaFiles';
import { useMediaViewerStore } from 'admin/vue/stores/mediaViewer';
import { eventBus } from 'admin/admin';
import { EVENT_MEDIA_SELECT, EVENT_MODAL_CLOSE, EVENT_TINY_EDITOR_ADD_MEDIA } from 'admin/events/events';
import MediaFileProperties from 'admin/vue/components/media/MediaFileProperties.vue';
import MediaFileSettings from 'admin/vue/components/media/MediaFileSettings.vue';
import MediaFile from 'admin/interfaces/MediaFile';

export default defineComponent({
    name: 'MediaBrowser',
    components: {
        MediaFileProperties,
        MediaFileSettings,
        MediaThumbnailsSelectionBar,
        MediaThumbnailsList,
        MediaViewer,
        FlashBar,
    },
    props: {
        getUrl: {
            type: String,
            required: true,
        },
        deleteUrl: {
            type: String,
            required: true,
        },
        mode: {
            type: String as () => 'extended' | 'minimal',
            required: true,
        },
    },
    setup(props) {
        const mediaFilesStore = useMediaFilesStore();
        const mediaViewerStore = useMediaViewerStore();

        mediaFilesStore.setup({ getUrl: props.getUrl, deleteUrl: props.deleteUrl });

        onMounted(() => {
            eventBus.on(EVENT_TINY_EDITOR_ADD_MEDIA, (callback) => {
                mediaViewerStore.callback = callback;
            });

            eventBus.on(EVENT_MEDIA_SELECT, (callback) => {
                mediaViewerStore.callback = callback;
            });
        });

        function onExtendedModeThumbnailClicked({ index }): void {
            if (!mediaFilesStore.selectMultiple) {
                mediaViewerStore.displayIndex = index;
                mediaViewerStore.show = true;
            }
        }

        function onMininmalModeThumbnailClicked({ index }): void {
            mediaViewerStore.displayIndex = index;
        }

        function selectFile(): void {
            if (mediaViewerStore.callback) {
                mediaViewerStore.callback({
                    files: [mediaFilesStore.mediaFiles[mediaViewerStore.displayIndex] as MediaFile],
                    settings: mediaViewerStore.settings,
                });
            }
            mediaViewerStore.show = false;

            eventBus.emit(EVENT_MODAL_CLOSE, { modalId: 'media_library' });
        }

        return {
            mediaFiles: computed(() => mediaFilesStore.mediaFiles),
            mediaFilesFilter: computed(() => mediaFilesStore.filter),
            selectedMediaFiles: computed(() => mediaFilesStore.selectedMediaFiles),
            displayIndex: computed(() => mediaViewerStore.displayIndex),
            onExtendedModeThumbnailClicked,
            onMininmalModeThumbnailClicked,
            clearMediaFilesSelection: mediaFilesStore.clearMediaFilesSelection,
            selectFile,
        };
    },
});
</script>
