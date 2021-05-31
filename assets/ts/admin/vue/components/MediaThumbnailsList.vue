<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
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
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import {
    EVENT_MEDIA_THUMBNAILS_LIST_THUMBNAIL_CLICKED,
    EVENT_MEDIA_THUMBNAILS_LIST_THUMBNAIL_SHIFT_CLICKED,
} from 'admin/events/events';
import { EventBus } from 'admin/admin';
import useMediaFileUtilities from 'admin/vue/functions/mediaFileUtilities';
import MediaFile from 'admin/interfaces/MediaFile';

export default defineComponent({
    name: 'MediaThumbnailsList',
    props: {
        mediaFiles: {
            type: Object as () => MediaFile[],
            required: true,
        },
        selectedMediaFiles: {
            type: Object as () => MediaFile[],
            required: true,
        },
    },
    emits: ['thumbnail-clicked', 'thumbnail-shift-clicked'],
    setup(props, { emit }) {
        const { imageUrl } = useMediaFileUtilities();

        function onThumbnailClicked(index: number) {
            const event = {
                index,
                file: props.mediaFiles[index],
            };

            EventBus.emit(EVENT_MEDIA_THUMBNAILS_LIST_THUMBNAIL_CLICKED, event);
            emit('thumbnail-clicked', event);
        }

        function onThumbnailShiftClicked(index: number) {
            const event = {
                index,
                file: props.mediaFiles[index],
            };

            EventBus.emit(EVENT_MEDIA_THUMBNAILS_LIST_THUMBNAIL_SHIFT_CLICKED, event);
            emit('thumbnail-shift-clicked', event);
        }

        function isMediaFileSelected(mediaFile: MediaFile): boolean {
            return !!props.selectedMediaFiles.find((f) => f.id === mediaFile.id);
        }

        return {
            imageUrl,
            onThumbnailClicked,
            onThumbnailShiftClicked,
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
