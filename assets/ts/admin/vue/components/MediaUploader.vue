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
        class="dropzone"
        :class="{ dragover: isDraggingOver, 'justify-center': files.length === 0, 'justify-between': files.length > 0 }"
        @dragover.prevent
        @dragenter.prevent="onDragEnter"
        @dragleave.prevent="onDragLeave"
        @drop.stop.prevent="onDrop"
    >
        <label class="w-full flex p-3">
            <span v-if="files.length === 0" class="flex-grow w-full text-center text-gray-800 text-md md:text-xl">
                Drop your files here or click to select
            </span>
            <input ref="fileInput" type="file" multiple @change="onDrop" />
            <slot>
                <draggable
                    v-if="files.length !== 0"
                    class="flex flex-wrap align-top gap-3"
                    :list="files"
                    :item-key="(file) => file.name"
                    group="files"
                >
                    <template #item="{ element, index }">
                        <MediaUploadableFile :file="element" @remove="removeFile(index)" />
                    </template>
                </draggable>
            </slot>
        </label>
        <button
            v-if="!autoUpload && files.length > 0"
            type="button"
            class="btn btn-color-primary m-3 self-start"
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
import MediaUploadableFile from 'admin/vue/components/MediaUploadableFile.vue';
import ParsedFile from 'admin/interfaces/ParsedFile';

interface MediaUploaderProps {
    uploadUrl: string;
    maxUploadSize: number;
    sequential?: boolean;
    autoUpload?: boolean;
}

export default defineComponent({
    name: 'MediaUploader',
    components: { MediaUploadableFile, draggable },
    props: {
        uploadUrl: {
            type: String,
            required: true,
        },
        maxUploadSize: {
            type: Number,
            required: true,
        },
        sequential: Boolean,
        autoUpload: Boolean,
    },
    setup(props: MediaUploaderProps) {
        const fileInput: Ref<HTMLInputElement> | Ref<null> = ref(null);

        const { isDraggingOver, onDragEnter, onDragLeave } = useDropzone();
        const { files, queueFilesForUpload, startUpload } = useFileUpload({
            uploadUrl: props.uploadUrl,
            maxUploadSize: props.maxUploadSize,
            sequential: props.sequential ?? false,
            autoUpload: props.autoUpload ?? true,
            onFileUploaded,
        });

        function onDrop(event: DragEvent): void {
            onDragLeave(event);

            if (
                (event.target &&
                    (event.target as HTMLInputElement).files &&
                    (event.target as HTMLInputElement).files!.length > 0) ||
                (event.dataTransfer && Object.keys(event.dataTransfer.files).length > 0)
            ) {
                const droppedFiles: File[] | FileList =
                    (event.target as HTMLInputElement)?.files || event.dataTransfer?.files || [];
                void queueFilesForUpload(droppedFiles as File[]);
            }

            fileInput.value!.value = '';
        }

        function removeFile(index) {
            files.value.splice(index, 1);
        }

        function onFileUploaded({ file }: { file: ParsedFile }) {
            files.value.splice(
                files.value.findIndex((f) => f.file.name === file.file.name),
                1,
            );
        }

        return {
            fileInput,
            files,
            isDraggingOver,
            onDragEnter,
            onDragLeave,
            onDrop,
            startUpload,
            removeFile,
        };
    },
});
</script>
<style lang="scss" scoped>
.dropzone {
    position: relative;
    overflow: hidden;

    @apply flex-grow flex flex-col items-center;

    &:not(.no-style) {
        @apply border border-2 border-dashed border-primary-300 bg-gray-100;
    }

    &.no-style {
        label {
            margin-bottom: 0;
        }
    }

    &.dragover {
        @apply text-primary border-solid border-primary ring-8 ring-inset ring-primary bg-gray-200;
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
