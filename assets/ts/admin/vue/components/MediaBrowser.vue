<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div v-if="mode === 'extended'" class="flex flex-col">
        <FlashBar class="sticky top-ui-area" />
        <div class="flex flex-col p-3">
            <MediaThumbnailsSelectionBar />
            <MediaThumbnailsList @thumbnail-clicked="onExtendedModeThumbnailClicked" />
        </div>
        <MediaViewer />
    </div>
    <div v-else class="flex flex-col">
        <MediaThumbnailsList @thumbnail-clicked="onMininmalModeThumbnailClicked" />
    </div>
</template>

<script lang="ts">
import { computed, defineComponent } from 'vue';
import FlashBar from 'admin/vue/components/FlashBar.vue';
import MediaViewer from 'admin/vue/components/MediaViewer.vue';
import MediaThumbnailsList from 'admin/vue/components/MediaThumbnailsList.vue';
import MediaThumbnailsSelectionBar from 'admin/vue/components/MediaThumbnailsSelectionBar.vue';
import { useMediaFilesStore } from 'admin/vue/stores/mediaFiles';
import { useMediaViewerStore } from 'admin/vue/stores/mediaViewer';

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
        const mediaFilesStore = useMediaFilesStore();
        const mediaViewerStore = useMediaViewerStore();

        mediaFilesStore.setup({ getUrl: props.getUrl, deleteUrl: props.deleteUrl });

        function onExtendedModeThumbnailClicked({ index }) {
            if (!mediaFilesStore.selectMultiple) {
                mediaViewerStore.displayIndex = index;
                mediaViewerStore.show = true;
            }
        }
        function onMininmalModeThumbnailClicked({ index }) {
            mediaViewerStore.displayIndex = index;
        }

        return {
            mediaFiles: computed(() => mediaFilesStore.mediaFiles),
            mediaFilesFilter: computed(() => mediaFilesStore.filter),
            selectedMediaFiles: computed(() => mediaFilesStore.selectedMediaFiles),
            onExtendedModeThumbnailClicked,
            onMininmalModeThumbnailClicked,
            clearMediaFilesSelection: mediaFilesStore.clearMediaFilesSelection,
        };
    },
});
</script>
