<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div class="flex-grow flex flex-col relative">
        <MediaResizeOptions
            v-model="resizeOptions"
            :class="{ 'absolute left-0 top-0 z-10': !resizeOptions.enabled, 'mb-3': resizeOptions.enabled }"
        />
        <div
            class="dropzone"
            :class="{
                dragover: isDraggingOver,
                'justify-center': files.length === 0,
                'justify-around': files.length > 0,
            }"
            @dragover.prevent
            @dragenter.prevent="onDragEnter"
            @dragleave.prevent="onDragLeave"
            @drop.stop.prevent="onDrop"
        >
            <label class="w-full h-full flex flex-col flex-grow p-3">
                <span v-if="files.length === 0" class="flex-grow flex items-center justify-center">
                    <span class="text-center text-gray-800 text-md md:text-xl">
                        Drop your files here or click to select
                    </span>
                </span>
                <input ref="fileInput" type="file" multiple @change="onDrop" />
                <slot>
                    <draggable
                        v-if="files.length !== 0"
                        class="flex flex-wrap gap-3"
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
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, Ref, ref } from 'vue';
import draggable from 'vuedraggable';
import useDropzone from 'admin/vue/functions/dropzone';
import useFileUpload from 'admin/vue/functions/fileUpload';
import MediaUploadableFile from 'admin/vue/components/MediaUploadableFile.vue';
import MediaResizeOptions from 'admin/vue/components/MediaResizeOptions.vue';
import ParsedFile from 'admin/interfaces/ParsedFile';
import ResizeOptions from 'admin/interfaces/ResizeOptions';
import { EventBus } from 'admin/admin';
import { EVENT_MEDIA_UPLOADER_FILE_UPLOADED } from 'admin/events/events';
import MediaFile from 'admin/interfaces/MediaFile';

interface MediaUploaderProps {
    uploadUrl: string;
    maxUploadSize: number;
    sequential?: boolean;
    autoUpload?: boolean;
}

export default defineComponent({
    name: 'MediaUploader',
    components: { MediaUploadableFile, MediaResizeOptions, draggable },
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
        const resizeOptions: ResizeOptions = reactive({
            enabled: false,
            width: 800,
            height: 800,
            quality: 75,
            mimeType: 'image/jpeg',
        });

        const { isDraggingOver, onDragEnter, onDragLeave } = useDropzone();
        const { files, queueFilesForUpload, startUpload } = useFileUpload({
            uploadUrl: props.uploadUrl,
            maxUploadSize: props.maxUploadSize,
            sequential: props.sequential ?? false,
            autoUpload: props.autoUpload ?? true,
            resizeOptions,
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

        function onFileUploaded({ parsedFile, mediaFile }: { parsedFile: ParsedFile; mediaFile: MediaFile }) {
            files.value.splice(
                files.value.findIndex((f) => f.file.name === parsedFile.file.name),
                1,
            );

            EventBus.emit(EVENT_MEDIA_UPLOADER_FILE_UPLOADED, {
                parsedFile,
                mediaFile,
                remainingCount: files.value.length,
            });
        }

        return {
            fileInput,
            resizeOptions,
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

    @apply flex-1 flex flex-col items-center;

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
