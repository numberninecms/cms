<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div class="uploadable-file" :class="{ error: file.error }" draggable="true" @click.prevent>
        <div class="relative">
            <img v-if="imageSource" :src="imageSource" class="square object-contain" alt="Loading..." />
            <div v-else class="square flex items-center justify-center">
                <i class="fa fa-file text-primary text-9xl" />
            </div>
        </div>

        <div class="flex flex-col mt-3">
            <p class="w-full text-center text-sm text-primary mb-2">{{ file.file.name }}</p>
            <div class="w-full flex flex-row justify-between items-center space-x-3">
                <div class="relative flex-grow">
                    <div class="overflow-hidden h-2 text-xs flex rounded bg-primary-100">
                        <div
                            :style="{ width: file.uploadProgress * 100 + '%' }"
                            class="
                                shadow-none
                                flex flex-col
                                text-center
                                whitespace-nowrap
                                text-white
                                justify-center
                                bg-primary
                            "
                        ></div>
                    </div>
                </div>
                <button @click.prevent="$emit('remove')">
                    <i class="fa fa-times text-red-600"></i>
                </button>
            </div>
            <div v-if="file.error" class="flex flex-col">
                <span v-if="file.error === 'file_too_big'" class="text-red-600 font-semibold">File is too big!</span>
                <span v-if="file.error === 'upload_error'" class="text-red-600 font-semibold">Upload failed!</span>
                <span v-if="file.image" class="text-gray-800 text-sm">Consider resizing before upload.</span>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { computed, defineComponent } from 'vue';
import ParsedFile from 'admin/interfaces/ParsedFile';

interface MediaUploadableFileProps {
    file: ParsedFile;
}

export default defineComponent({
    name: 'MediaUploadableFile',
    props: {
        file: {
            type: Object as () => ParsedFile,
            required: true,
        },
    },
    emits: ['remove'],
    setup(props: MediaUploadableFileProps) {
        const imageSource = computed(() => (props.file.thumbnail ? props.file.thumbnail.src : null));

        return {
            imageSource,
        };
    },
});
</script>
<style lang="scss" scoped>
$square-size: 250px;

.uploadable-file {
    @apply cursor-move shadow-md p-3 flex flex-col bg-white;
    min-height: 20rem;
    max-width: $square-size;

    &.error {
        @apply ring-2 ring-red-600 ring-inset;
    }

    .square {
        position: relative;
        width: $square-size;
        height: $square-size;
    }
}
</style>
