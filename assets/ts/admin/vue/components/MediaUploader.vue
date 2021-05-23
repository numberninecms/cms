<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div
        class="dropzone h-24"
        :class="{ dragover: isDraggingOver }"
        @dragover.prevent
        @dragenter.prevent="onDragEnter"
        @dragleave.prevent="onDragLeave"
        @drop.stop.prevent="onDrop"
    >
        <label class="w-full flex p-3">
            <span v-if="files.length === 0" class="flex-grow w-full text-center">
                Drop your files here or click to select
            </span>
            <input ref="fileInput" type="file" multiple @change="onDrop" />
            <slot>
                <draggable
                    v-if="files.length !== 0"
                    class="flex flex-wrap space-x-3 space-y-3"
                    :list="files"
                    group="files"
                >
                    <!--                    <MediaUploadableFile v-for="(file, index) in files" :key="file.file.name" :file="file" @remove="removeFile(index)" :default-thumbnail="defaultThumbnail" class="p-2 m-1" />-->
                </draggable>
            </slot>
        </label>
        <button
            v-if="!autoUpload && files.length > 0"
            type="button"
            class="btn btn-color-primary"
            @click="startUpload"
        >
            Start upload
        </button>
    </div>
</template>

<script lang="ts">
import { defineComponent, Ref, ref } from 'vue';
import draggable from 'vuedraggable';
import useDropzone from '../functions/dropzone';
import useFileUpload from '../functions/fileUpload';

interface MediaUploaderProps {
    uploadUrl: string;
    maxUploadSize: number;
    sequential?: boolean;
    autoUpload?: boolean;
}

export default defineComponent({
    name: 'MediaUploader',
    components: { draggable },
    setup(props: MediaUploaderProps) {
        const fileInput: Ref<HTMLInputElement> | Ref<null> = ref(null);

        const { isDraggingOver, onDragEnter, onDragLeave } = useDropzone();
        const { files, queueFilesForUpload, startUpload } = useFileUpload({
            uploadUrl: props.uploadUrl,
            maxUploadSize: props.maxUploadSize,
            sequential: props.sequential ?? false,
            autoUpload: props.autoUpload ?? false,
            onFileUploaded: (file, data) => {
                console.log('file uploaded');
            },
        });

        function onDrop(event: DragEvent): void {
            onDragLeave(event);

            if (
                (event.target &&
                    (event.target as HTMLInputElement).files &&
                    (event.target as HTMLInputElement).files!.length > 0) ||
                (event.dataTransfer && Object.keys(event.dataTransfer.files).length > 0)
            ) {
                const files: File[] | FileList =
                    (event.target as HTMLInputElement)?.files || event.dataTransfer?.files || [];
                void queueFilesForUpload(files as File[]);
            }

            fileInput.value!.value = '';
        }

        return {
            autoUpload: props.autoUpload,
            fileInput,
            files,
            isDraggingOver,
            onDragEnter,
            onDragLeave,
            onDrop,
            startUpload,
        };
    },
});
</script>
<style lang="scss" scoped>
.dropzone {
    position: relative;
    overflow: hidden;

    &:not(.no-style) {
        @apply border border-dashed border-gray-500 bg-gray-300;
    }

    &.no-style {
        label {
            margin-bottom: 0;
        }
    }

    &.dragover {
        @apply text-primary border border-solid border-gray-700;
    }

    input[type='file'] {
        position: absolute;
        opacity: 0;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }
}
</style>
