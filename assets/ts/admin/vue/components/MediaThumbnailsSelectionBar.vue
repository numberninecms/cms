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
            <input v-model="checkbox" type="checkbox" @update:modelValue="$emit('update:select-multiple', $event)" />
            <span>Select multiple files</span>
        </label>

        <button
            v-if="selectedMediaFiles.length > 0"
            class="btn btn-color-red btn-size-xsmall space-x-3"
            type="button"
            title="Delete selected media"
            @click="$emit('delete-selected-files-clicked')"
        >
            <i class="fa fa-trash"></i>
            <span class="hidden md:inline">Delete selected media</span>
        </button>

        <button
            v-if="selectedMediaFiles.length > 0"
            class="btn btn-color-white btn-style-outline btn-size-xsmall space-x-3"
            type="button"
            title="Clear selection"
            @click="$emit('clear-selection-clicked')"
        >
            <i class="fa fa-eraser"></i>
            <span class="hidden md:inline">Clear selection</span>
        </button>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import MediaFile from 'admin/interfaces/MediaFile';

export default defineComponent({
    name: 'MediaThumbnailsSelectionBar',
    props: {
        mediaFiles: {
            type: Object as () => MediaFile[],
            required: true,
        },
        selectedMediaFiles: {
            type: Object as () => MediaFile[],
            required: true,
        },
        selectMultiple: Boolean,
    },
    emits: ['update:select-multiple', 'delete-selected-files-clicked', 'clear-selection-clicked'],
    setup() {
        const checkbox = ref(false);

        return {
            checkbox,
        };
    },
});
</script>
