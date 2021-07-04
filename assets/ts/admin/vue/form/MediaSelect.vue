<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div class="flex flex-col items-start gap-3 mt-3">
        <div v-if="file">
            <img :src="thumbnail" class="h-24" />
        </div>
        <div class="flex gap-3">
            <button type="button" class="btn btn-color-primary" @click="openMediaLibrary">Select image</button>
            <button v-if="file" type="button" class="btn btn-color-red" @click="removeImage">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, ref, Ref } from 'vue';
import MediaFile from 'admin/interfaces/MediaFile';
import { useContentEntityStore } from 'admin/vue/stores/contentEntity';
import { dirname } from 'path';
import { eventBus } from 'admin/admin';
import { EVENT_MEDIA_SELECT, EVENT_MODAL_SHOW } from 'admin/events/events';

export default defineComponent({
    name: 'MediaSelect',
    props: {
        modelValue: {
            type: [String, Number],
            default: undefined,
        },
        color: {
            type: String,
            default: 'primary',
        },
    },
    emits: ['update:modelValue', 'input-computed'],
    setup(props, { emit }) {
        const contentEntityStore = useContentEntityStore();
        const file: Ref<MediaFile | undefined> = ref(undefined);

        onMounted(async () => {
            if (props.modelValue) {
                file.value = (await contentEntityStore.fetchSingleEntityById(
                    typeof props.modelValue === 'string' ? parseInt(props.modelValue) : props.modelValue ?? 0,
                )) as MediaFile;
                emit('input-computed', file.value);
            }
        });

        const thumbnail = computed(() => {
            return file.value ? `${dirname(file.value.path)}/${file.value.sizes.preview.filename}` : '';
        });

        function openMediaLibrary() {
            eventBus.emit(EVENT_MODAL_SHOW, { modalId: 'media_library' });
            eventBus.emit(EVENT_MEDIA_SELECT, ({ files }) => {
                if (files.length > 0) {
                    file.value = files[0];
                    emit('update:modelValue', file.value?.id);
                    emit('input-computed', file.value);
                }
            });
        }

        function removeImage() {
            file.value = undefined;
            emit('update:modelValue', undefined);
        }

        return {
            thumbnail,
            file,
            openMediaLibrary,
            removeImage,
        };
    },
});
</script>
