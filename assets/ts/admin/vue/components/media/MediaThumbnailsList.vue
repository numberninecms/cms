<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div ref="mediaBrowser" class="flex flex-col">
        <div class="flex flex-wrap gap-3">
            <div
                v-for="(mediaFile, index) in mediaFiles"
                :key="mediaFile.id"
                class="mediafile shadow-lg flex items-center"
                :class="{ selected: isMediaFileSelected(mediaFile) }"
                @click.exact="onThumbnailClicked(index)"
                @click.shift.exact="onThumbnailShiftClicked(index)"
            >
                <img v-if="imageUrl(mediaFile)" :src="imageUrl(mediaFile)" :alt="mediaFile.title" />
                <div v-else class="flex items-center justify-center">
                    <i class="fa fa-file text-primary text-7xl" />
                </div>
            </div>
        </div>
        <div v-observe-visibility="endOfListVisibilityChanged"></div>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, nextTick, onMounted, ref, watch } from 'vue';
import {
    EVENT_MEDIA_THUMBNAILS_LIST_THUMBNAIL_CLICKED,
    EVENT_MEDIA_THUMBNAILS_LIST_THUMBNAIL_SHIFT_CLICKED,
    EVENT_MEDIA_UPLOADER_FILE_UPLOADED,
    EVENT_MODAL_VISIBILITY_CHANGED,
} from 'admin/events/events';
import { eventBus } from 'admin/admin';
import useMediaFileUtilities from 'admin/vue/functions/mediaFileUtilities';
import { useMediaFilesStore } from 'admin/vue/stores/mediaFiles';
import MediaFile from 'admin/interfaces/MediaFile';
import { useMediaViewerStore } from 'admin/vue/stores/mediaViewer';
import MediaLibraryThumbnailClickedEvent from 'admin/events/MediaLibraryThumbnailClickedEvent';

export default defineComponent({
    name: 'MediaThumbnailsList',
    emits: ['thumbnail-clicked', 'thumbnail-shift-clicked'],
    setup(props, { emit }) {
        const mediaFilesStore = useMediaFilesStore();
        const mediaViewerStore = useMediaViewerStore();
        const { imageUrl } = useMediaFileUtilities();
        const mediaBrowser = ref(null);
        let isLoadingMore = false;
        let isModal = false;
        const isEndOfListVisible = ref(false);

        onMounted(() => {
            eventBus.on(EVENT_MEDIA_UPLOADER_FILE_UPLOADED, (event) => {
                mediaFilesStore.mediaFiles.splice(0, 0, event.mediaFile);
            });

            isModal = (mediaBrowser.value as unknown as HTMLElement)!.closest('.modal-backdrop') !== null;

            if (!isModal) {
                void loadMoreMediaFiles();
            } else {
                eventBus.on(EVENT_MODAL_VISIBILITY_CHANGED, (event) => {
                    if (event.visible) {
                        void loadMoreMediaFiles();
                    }
                });
            }
        });

        watch(
            mediaFilesStore.mediaFiles,
            async () => {
                if (mediaFilesStore.mediaFiles.length === 0) {
                    return;
                }

                await nextTick();

                if (isEndOfListVisible.value) {
                    await loadMoreMediaFiles();
                }
            },
            { deep: true },
        );

        async function loadMoreMediaFiles(): Promise<void> {
            isLoadingMore = true;
            await mediaFilesStore.loadMoreMediaFiles();
            isLoadingMore = false;
        }

        function endOfListVisibilityChanged(isVisible) {
            isEndOfListVisible.value = isVisible;

            if (mediaFilesStore.mediaFiles.length === 0) {
                return;
            }

            if (isEndOfListVisible.value && !isLoadingMore) {
                void loadMoreMediaFiles();
            }
        }

        function onThumbnailClicked(index: number) {
            const event: MediaLibraryThumbnailClickedEvent = {
                index,
                file: mediaFilesStore.mediaFiles[index],
            };

            if (mediaFilesStore.selectMultiple) {
                mediaFilesStore.setBulkSelectFirstIndex(index);
            }

            eventBus.emit(EVENT_MEDIA_THUMBNAILS_LIST_THUMBNAIL_CLICKED, event);
            emit('thumbnail-clicked', event);
        }

        function onThumbnailShiftClicked(index: number) {
            const event = {
                index,
                file: mediaFilesStore.mediaFiles[index],
            };

            if (mediaFilesStore.selectMultiple) {
                mediaFilesStore.bulkMediaSelect(index);
            }

            eventBus.emit(EVENT_MEDIA_THUMBNAILS_LIST_THUMBNAIL_SHIFT_CLICKED, event);

            emit('thumbnail-shift-clicked', event);
        }

        function isMediaFileSelected(mediaFile: MediaFile): boolean {
            return !isModal
                ? mediaFilesStore.isMediaFileSelected(mediaFile)
                : mediaViewerStore.isMediaFileDisplayed(mediaFile);
        }

        return {
            mediaBrowser,
            imageUrl,
            mediaFiles: computed(() => mediaFilesStore.mediaFiles),
            onThumbnailClicked,
            onThumbnailShiftClicked,
            endOfListVisibilityChanged,
            isMediaFileSelected,
        };
    },
});
</script>

<style lang="scss" scoped>
.mediafile {
    @apply cursor-pointer;
    width: 105px;
    height: 105px;

    &.selected {
        @apply ring-2 ring-primary;
    }
}

@screen md {
    .mediafile {
        width: 150px;
        height: 150px;
    }
}
</style>
