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
        <div ref="endOfList"></div>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, nextTick, onMounted, ref, watch } from 'vue';
import {
    EVENT_MEDIA_THUMBNAILS_LIST_THUMBNAIL_CLICKED,
    EVENT_MEDIA_THUMBNAILS_LIST_THUMBNAIL_SHIFT_CLICKED,
    EVENT_MEDIA_UPLOADER_FILE_UPLOADED,
} from 'admin/events/events';
import { EventBus } from 'admin/admin';
import useMediaFileUtilities from 'admin/vue/functions/mediaFileUtilities';
import { useMediaFilesStore } from 'admin/vue/stores/mediaFiles';
import { useElementVisibility } from '@vueuse/core';

export default defineComponent({
    name: 'MediaThumbnailsList',
    emits: ['thumbnail-clicked', 'thumbnail-shift-clicked'],
    setup(props, { emit }) {
        const store = useMediaFilesStore();
        const { imageUrl } = useMediaFileUtilities();

        const endOfList = ref(null);
        const endOfListIsVisible = ref(false);
        const endOfListIsVisibleAfterScroll = useElementVisibility(endOfList);
        let isLoadingMore = false;

        onMounted(async () => {
            EventBus.on(EVENT_MEDIA_UPLOADER_FILE_UPLOADED, ({ mediaFile }) => {
                store.mediaFiles.splice(0, 0, mediaFile);
            });

            isLoadingMore = true;
            await store.loadMoreMediaFiles();
            isLoadingMore = false;
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
                    isLoadingMore = true;
                    await store.loadMoreMediaFiles();
                    isLoadingMore = false;
                }
            },
            { deep: true },
        );

        watch(endOfListIsVisibleAfterScroll, async () => {
            if (endOfListIsVisibleAfterScroll.value && !isLoadingMore) {
                isLoadingMore = true;
                await store.loadMoreMediaFiles();
                isLoadingMore = false;
            }
        });

        function onThumbnailClicked(index: number) {
            const event = {
                index,
                file: store.mediaFiles[index],
            };

            if (store.selectMultiple) {
                store.setBulkSelectFirstIndex(index);
            }

            EventBus.emit(EVENT_MEDIA_THUMBNAILS_LIST_THUMBNAIL_CLICKED, event);
            emit('thumbnail-clicked', event);
        }

        function onThumbnailShiftClicked(index: number) {
            const event = {
                index,
                file: store.mediaFiles[index],
            };

            if (store.selectMultiple) {
                store.bulkMediaSelect(index);
            }

            EventBus.emit(EVENT_MEDIA_THUMBNAILS_LIST_THUMBNAIL_SHIFT_CLICKED, event);
            emit('thumbnail-shift-clicked', event);
        }

        return {
            endOfList,
            imageUrl,
            mediaFiles: computed(() => store.mediaFiles),
            onThumbnailClicked,
            onThumbnailShiftClicked,
            isMediaFileSelected: store.isMediaFileSelected,
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
