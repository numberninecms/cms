<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div>
        <div class="flex flex-wrap gap-3">
            <div v-for="mediaFile in mediaFiles" :key="mediaFile.id" class="shadow-lg">
                <div class="mediafile">
                    <img v-if="thumbnail(mediaFile)" :src="thumbnail(mediaFile)" :alt="mediaFile.title" />
                    <div v-else class="flex items-center justify-center">
                        <i class="fa fa-file text-primary text-7xl" />
                    </div>
                </div>
            </div>
        </div>
        <div ref="endOfList"></div>
    </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref, watch, watchEffect } from 'vue';
import useMediaStore from 'admin/vue/functions/mediaStore';
import MediaFile from 'admin/interfaces/MediaFile';
import path from 'path';
import { useElementVisibility } from '@vueuse/core';
import { EVENT_MEDIA_UPLOADER_FILE_UPLOADED } from 'admin/events/events';
import { EventBus } from 'admin/admin';
import ParsedFile from 'admin/interfaces/ParsedFile';

interface MediaBrowserProps {
    mediaUrl: string;
}

export default defineComponent({
    name: 'MediaBrowser',
    props: {
        mediaUrl: {
            type: String,
            required: true,
        },
    },
    setup(props: MediaBrowserProps) {
        const endOfList = ref(null);
        let endOfListIsVisible = useElementVisibility(endOfList);

        const { mediaFiles, mediaFilesFilter, loadMoreMediaFiles } = useMediaStore({
            mediaUrl: props.mediaUrl,
        });

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

        watchEffect(() => {
            mediaFiles.value.length; // This line triggers watch effect and is needed. Don't know why.

            if (endOfListIsVisible.value) {
                void loadMoreMediaFiles();
            }
        });

        function thumbnail(mediaFile: MediaFile) {
            return mediaFile.mimeType.substr(0, 5) === 'image'
                ? `${path.dirname(mediaFile.path)}/${mediaFile.sizes.thumbnail.filename}`
                : null;
        }

        return {
            mediaFiles,
            mediaFilesFilter,
            endOfList,
            thumbnail,
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
