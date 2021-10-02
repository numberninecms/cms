<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div class="flex items-center space-x-5 pb-3 h-8">
        <label class="space-x-3" title="Hold SHIFT to select a range of files">
            <input v-model="checkbox" type="checkbox" />
            <span>Select multiple files</span>
        </label>

        <button
            v-if="selectedMediaFiles.length > 0"
            class="btn btn-color-red btn-size-xsmall space-x-3"
            type="button"
            title="Delete selected media"
            @click="deleteSelection"
        >
            <i class="fa fa-trash"></i>
            <span class="hidden md:inline">Delete selected media</span>
        </button>

        <button
            v-if="selectedMediaFiles.length > 0"
            class="btn btn-color-white btn-style-outline btn-size-xsmall space-x-3"
            type="button"
            title="Clear selection"
            @click="clearMediaFilesSelection"
        >
            <i class="fa fa-eraser"></i>
            <span class="hidden md:inline">Clear selection</span>
        </button>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, ref, watch } from 'vue';
import { useMediaFilesStore } from 'admin/vue/stores/mediaFiles';
import { useFlashesStore } from 'admin/vue/stores/flashes';
import MediaFile from 'admin/interfaces/MediaFile';

export default defineComponent({
    name: 'MediaThumbnailsSelectionBar',
    setup() {
        const mediaFilesStore = useMediaFilesStore();
        const flashesStore = useFlashesStore();
        const checkbox = ref(false);

        onMounted(() => {
            checkbox.value = mediaFilesStore.selectMultiple;
        });

        watch(checkbox, () => {
            mediaFilesStore.selectMultiple = checkbox.value;
        });

        async function deleteSelection() {
            try {
                await mediaFilesStore.deleteMediaFiles([...(mediaFilesStore.selectedMediaFiles as MediaFile[])]);
                mediaFilesStore.clearMediaFilesSelection();

                flashesStore.label = 'success';
                flashesStore.message = 'Media files removed successfully.';
            } catch (e) {
                flashesStore.label = 'error';
                flashesStore.message = 'Unable to remove media files.';
            }

            flashesStore.visible = true;
        }

        return {
            checkbox,
            mediaFiles: computed(() => mediaFilesStore.mediaFiles),
            selectedMediaFiles: computed(() => mediaFilesStore.selectedMediaFiles),
            deleteSelection,
            clearMediaFilesSelection: mediaFilesStore.clearMediaFilesSelection,
        };
    },
});
</script>
