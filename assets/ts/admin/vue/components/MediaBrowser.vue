<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div class="flex flex-col">
        <FlashBar
            v-model:visible="isFlashVisible"
            :label="flashLabel"
            :message="flashMessage"
            class="sticky top-ui-area"
        />
        <div class="flex flex-col p-3">
            <MediaThumbnailsSelectionBar
                v-model:select-multiple="selectMultipleMediaFiles"
                :media-files="mediaFiles"
                :selected-media-files="selectedMediaFiles"
                @delete-selected-files-clicked="deleteSelection"
                @clear-selection-clicked="clearMediaFilesSelection"
            />
            <MediaThumbnailsList
                :media-files="mediaFiles"
                :selected-media-files="selectedMediaFiles"
                @thumbnail-clicked="onThumbnailClicked"
                @thumbnail-shift-clicked="onThumbnailShiftClicked"
            />
            <div ref="endOfList"></div>
        </div>
        <MediaViewer
            v-if="displayIndex !== -1"
            v-model:show="showViewer"
            :media-file="mediaFiles[displayIndex]"
            @previous="viewPreviousMediaFile"
            @next="viewNextMediaFile"
        />
    </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref, watch, watchEffect } from 'vue';
import { useElementVisibility } from '@vueuse/core';
import { EVENT_MEDIA_UPLOADER_FILE_UPLOADED } from 'admin/events/events';
import { EventBus } from 'admin/admin';
import useMediaBrowserSelection from 'admin/vue/functions/mediaBrowserSelection';
import useMediaBrowserFilesLoader from 'admin/vue/functions/mediaBrowserFilesLoader';
import FlashBar from 'admin/vue/components/FlashBar.vue';
import MediaViewer from 'admin/vue/components/MediaViewer.vue';
import MediaThumbnailsList from 'admin/vue/components/MediaThumbnailsList.vue';
import MediaThumbnailsSelectionBar from 'admin/vue/components/MediaThumbnailsSelectionBar.vue';

export default defineComponent({
    name: 'MediaBrowser',
    components: { MediaThumbnailsSelectionBar, MediaThumbnailsList, MediaViewer, FlashBar },
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
        const endOfList = ref(null);
        let endOfListIsVisible = useElementVisibility(endOfList);

        const flashLabel = ref('');
        const flashMessage = ref('');
        const isFlashVisible = ref(false);
        const showViewer = ref(true);
        const displayIndex = ref(-1);

        const { mediaFiles, mediaFilesFilter, loadMoreMediaFiles, deleteMediaFiles } = useMediaBrowserFilesLoader({
            getUrl: props.getUrl,
            deleteUrl: props.deleteUrl,
        });

        const {
            selectedMediaFiles,
            setBulkSelectFirstIndex,
            bulkMediaSelect,
            selectMultipleMediaFiles,
            clearMediaFilesSelection,
        } = useMediaBrowserSelection({ mediaFiles });

        onMounted(() => {
            EventBus.on(EVENT_MEDIA_UPLOADER_FILE_UPLOADED, ({ mediaFile }) => {
                mediaFiles.value.splice(0, 0, mediaFile);
            });
        });

        watch(
            () => [...mediaFiles.value],
            () => {
                endOfListIsVisible = useElementVisibility(endOfList);
            },
        );

        watch(showViewer, () => {
            if (!showViewer.value) {
                displayIndex.value = -1;
            }
        });

        watchEffect(() => {
            mediaFiles.value.length; // This line triggers watch effect and is needed. Don't know why.

            if (endOfListIsVisible.value) {
                void loadMoreMediaFiles();
            }
        });

        async function deleteSelection() {
            try {
                await deleteMediaFiles([...selectedMediaFiles.value]);
                clearMediaFilesSelection();

                flashLabel.value = 'success';
                flashMessage.value = 'Media files removed successfully.';
            } catch (e) {
                flashLabel.value = 'error';
                flashMessage.value = 'Unable to remove media files.';
            }

            isFlashVisible.value = true;
        }

        function onThumbnailClicked({ index }) {
            if (selectMultipleMediaFiles.value) {
                setBulkSelectFirstIndex(index);
            } else {
                displayIndex.value = index;
                showViewer.value = true;
            }
        }

        function onThumbnailShiftClicked({ index }) {
            bulkMediaSelect(index);
        }

        function viewPreviousMediaFile(): void {
            if (displayIndex.value > 0) {
                displayIndex.value--;
            } else {
                displayIndex.value = mediaFiles.value.length - 1;
            }
        }

        function viewNextMediaFile(): void {
            if (displayIndex.value < mediaFiles.value.length - 1) {
                displayIndex.value++;
            } else {
                displayIndex.value = 0;
            }
        }

        return {
            mediaFiles,
            mediaFilesFilter,
            endOfList,
            selectedMediaFiles,
            onThumbnailClicked,
            onThumbnailShiftClicked,
            selectMultipleMediaFiles,
            clearMediaFilesSelection,
            deleteSelection,
            flashLabel,
            flashMessage,
            isFlashVisible,
            showViewer,
            displayIndex,
            viewPreviousMediaFile,
            viewNextMediaFile,
        };
    },
});
</script>
