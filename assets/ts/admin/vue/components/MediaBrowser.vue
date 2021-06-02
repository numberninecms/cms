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
import { computed, defineComponent, nextTick, onMounted, ref, watch } from 'vue';
import { useElementVisibility } from '@vueuse/core';
import { EVENT_MEDIA_UPLOADER_FILE_UPLOADED } from 'admin/events/events';
import { EventBus } from 'admin/admin';
import FlashBar from 'admin/vue/components/FlashBar.vue';
import MediaViewer from 'admin/vue/components/MediaViewer.vue';
import MediaThumbnailsList from 'admin/vue/components/MediaThumbnailsList.vue';
import MediaThumbnailsSelectionBar from 'admin/vue/components/MediaThumbnailsSelectionBar.vue';
import { useMediaFilesStore } from 'admin/vue/stores/mediaFiles';

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
        const endOfListIsVisible = ref(false);
        const endOfListIsVisibleAfterScroll = useElementVisibility(endOfList);
        const flashLabel = ref('');
        const flashMessage = ref('');
        const isFlashVisible = ref(false);
        const showViewer = ref(true);
        const displayIndex = ref(-1);
        const selectMultipleMediaFiles = ref(false);
        const isLoadingMore = ref(false);

        const store = useMediaFilesStore();
        store.setup({ getUrl: props.getUrl, deleteUrl: props.deleteUrl });

        onMounted(async () => {
            EventBus.on(EVENT_MEDIA_UPLOADER_FILE_UPLOADED, ({ mediaFile }) => {
                store.mediaFiles.splice(0, 0, mediaFile);
            });

            isLoadingMore.value = true;
            await store.loadMoreMediaFiles();
            isLoadingMore.value = false;
        });

        watch(
            store.mediaFiles,
            async () => {
                await nextTick();

                const rect = (endOfList.value as unknown as HTMLElement)!.getBoundingClientRect();
                endOfListIsVisible.value =
                    rect.top <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.left <= (window.innerWidth || document.documentElement.clientWidth) &&
                    rect.bottom >= 0 &&
                    rect.right >= 0;

                if (endOfListIsVisible.value) {
                    isLoadingMore.value = true;
                    await store.loadMoreMediaFiles();
                    isLoadingMore.value = false;
                }
            },
            { deep: true },
        );

        watch(endOfListIsVisibleAfterScroll, async () => {
            if (endOfListIsVisibleAfterScroll.value && !isLoadingMore.value) {
                isLoadingMore.value = true;
                await store.loadMoreMediaFiles();
                isLoadingMore.value = false;
            }
        });

        watch(showViewer, () => {
            if (!showViewer.value) {
                displayIndex.value = -1;
            }
        });

        watch(selectMultipleMediaFiles, () => {
            store.selectMultiple = selectMultipleMediaFiles.value;
        });

        async function deleteSelection() {
            try {
                await store.deleteMediaFiles([...store.selectedMediaFiles]);
                store.clearMediaFilesSelection();

                flashLabel.value = 'success';
                flashMessage.value = 'Media files removed successfully.';
            } catch (e) {
                flashLabel.value = 'error';
                flashMessage.value = 'Unable to remove media files.';
            }

            isFlashVisible.value = true;
        }

        function onThumbnailClicked({ index }) {
            if (store.selectMultiple) {
                store.setBulkSelectFirstIndex(index);
            } else {
                displayIndex.value = index;
                showViewer.value = true;
            }
        }

        function onThumbnailShiftClicked({ index }) {
            store.bulkMediaSelect(index);
        }

        function viewPreviousMediaFile(): void {
            if (displayIndex.value > 0) {
                displayIndex.value--;
            } else {
                displayIndex.value = store.mediaFiles.length - 1;
            }
        }

        function viewNextMediaFile(): void {
            if (displayIndex.value < store.mediaFiles.length - 1) {
                displayIndex.value++;
            } else {
                displayIndex.value = 0;
            }
        }

        return {
            mediaFiles: computed(() => store.mediaFiles),
            mediaFilesFilter: computed(() => store.filter),
            endOfList,
            selectedMediaFiles: computed(() => store.selectedMediaFiles),
            onThumbnailClicked,
            onThumbnailShiftClicked,
            selectMultipleMediaFiles,
            clearMediaFilesSelection: store.clearMediaFilesSelection,
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
