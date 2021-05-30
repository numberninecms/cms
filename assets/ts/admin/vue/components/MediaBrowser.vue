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
            <div class="flex items-center space-x-5 pb-3 h-8">
                <label class="space-x-3" title="Hold SHIFT to select a range of files">
                    <input v-model="selectMultipleMediaFiles" type="checkbox" />
                    <span>Select multiple files</span>
                </label>

                <button
                    v-if="selectedMediaFilesCount > 0"
                    class="btn btn-color-red btn-size-xsmall space-x-3"
                    type="button"
                    title="Delete selected media"
                    @click="deleteSelection"
                >
                    <i class="fa fa-trash"></i>
                    <span class="hidden md:inline">Delete selected media</span>
                </button>

                <button
                    v-if="selectedMediaFilesCount > 0"
                    class="btn btn-color-white btn-style-outline btn-size-xsmall space-x-3"
                    type="button"
                    title="Clear selection"
                    @click="clearMediaFilesSelection"
                >
                    <i class="fa fa-eraser"></i>
                    <span class="hidden md:inline">Clear selection</span>
                </button>
            </div>
            <div class="flex flex-wrap gap-3">
                <div
                    v-for="(mediaFile, index) in mediaFiles"
                    :key="mediaFile.id"
                    class="mediafile shadow-lg flex items-center"
                    :class="{ selected: isMediaFileSelected(mediaFile) }"
                    @click.exact="onThumbnailClicked(index)"
                    @click.shift.exact="bulkMediaSelect(index)"
                >
                    <img v-if="imageUrl(mediaFile)" :src="imageUrl(mediaFile)" :alt="mediaFile.title" />
                    <div v-else class="flex items-center justify-center">
                        <i class="fa fa-file text-primary text-7xl" />
                    </div>
                </div>
            </div>
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
import { computed, defineComponent, onMounted, ref, watch, watchEffect } from 'vue';
import { useElementVisibility } from '@vueuse/core';
import { EVENT_MEDIA_UPLOADER_FILE_UPLOADED } from 'admin/events/events';
import { EventBus } from 'admin/admin';
import useMediaBrowserSelection from 'admin/vue/functions/mediaBrowserSelection';
import useMediaBrowserFilesLoader from 'admin/vue/functions/mediaBrowserFilesLoader';
import FlashBar from 'admin/vue/components/FlashBar.vue';
import MediaViewer from 'admin/vue/components/MediaViewer.vue';
import useMediaFileUtilities from 'admin/vue/functions/mediaFileUtilities';

export default defineComponent({
    name: 'MediaBrowser',
    components: { MediaViewer, FlashBar },
    props: {
        getUrl: {
            type: String,
            required: true,
        },
        deleteUrl: {
            type: String,
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
            isMediaFileSelected,
            setBulkSelectFirstIndex,
            bulkMediaSelect,
            selectMultipleMediaFiles,
            clearMediaFilesSelection,
        } = useMediaBrowserSelection({ mediaFiles });

        const { imageUrl } = useMediaFileUtilities();

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

        function onThumbnailClicked(index: number) {
            if (selectMultipleMediaFiles.value) {
                setBulkSelectFirstIndex(index);
            } else {
                displayIndex.value = index;
                showViewer.value = true;
            }
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
            imageUrl,
            isMediaFileSelected,
            onThumbnailClicked,
            bulkMediaSelect,
            selectMultipleMediaFiles,
            clearMediaFilesSelection,
            selectedMediaFilesCount: computed(() => selectedMediaFiles.value.length),
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

<style lang="scss" scoped>
.mediafile {
    @apply cursor-pointer;
    width: 105px;
    height: 105px;
}

.selected {
    @apply ring-2 ring-primary;
}

@screen md {
    .mediafile {
        width: 150px;
        height: 150px;
    }
}
</style>
