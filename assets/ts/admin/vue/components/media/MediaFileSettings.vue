<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div class="flex flex-col gap-3 mb-3">
        <div>
            <div class="flex flex-col px-3 mt-2">
                <label class="font-semibold text-quaternary" for="settings_form_title">Title</label>
                <input id="settings_form_title" v-model="mediaFile.title" type="text" />
            </div>
            <div class="flex flex-col px-3 mt-2">
                <label class="font-semibold text-quaternary" for="settings_form_caption">Caption</label>
                <input id="settings_form_caption" v-model="mediaFile.caption" type="text" />
            </div>
            <div class="flex flex-col px-3 mt-2">
                <label class="font-semibold text-quaternary" for="settings_form_alt">Alternative text</label>
                <input id="settings_form_alt" v-model="mediaFile.alternativeText" type="text" />
            </div>
            <div class="flex flex-col px-3 mt-2">
                <label class="font-semibold text-quaternary" for="settings_form_content">Description</label>
                <input id="settings_form_content" v-model="mediaFile.content" type="text" />
            </div>
        </div>
        <div class="flex flex-col px-3 mt-2">
            <p class="font-light text-lg">Media settings</p>
            <div class="flex flex-col px-3 mt-2">
                <label class="font-semibold text-quaternary" for="settings_form_size">Size</label>
                <select id="settings_form_size" v-model="settings.size">
                    <option v-for="option in fileSizesOptions" :key="option.value" :value="option.value">
                        {{ option.label }}
                    </option>
                </select>
            </div>
            <div class="flex flex-col px-3 mt-2">
                <label class="font-semibold text-quaternary" for="settings_form_alignment">Alignment</label>
                <select id="settings_form_alignment" v-model="settings.alignment">
                    <option v-for="option in alignmentOptions" :key="option.value" :value="option.value">
                        {{ option.label }}
                    </option>
                </select>
            </div>
            <div class="flex flex-col px-3 mt-2">
                <label class="font-semibold text-quaternary" for="settings_form_link">Link</label>
                <select id="settings_form_link" v-model="settings.link">
                    <option v-for="option in linkOptions" :key="option.value" :value="option.value">
                        {{ option.label }}
                    </option>
                </select>
            </div>
            <div v-if="settings.link === 'custom'" class="flex flex-col px-3 mt-2">
                <label class="font-semibold text-quaternary" for="settings_form_link_url">Link URL</label>
                <input id="settings_form_link_url" v-model="settings.linkUrl" type="text" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, reactive, watch } from 'vue';
import { useMediaFilesStore } from 'admin/vue/stores/mediaFiles';
import { useMediaViewerStore } from 'admin/vue/stores/mediaViewer';
import MediaSettings from 'admin/interfaces/MediaSettings';
import SelectOption from 'admin/interfaces/SelectOption';
import { capitalCase } from 'change-case';

export default defineComponent({
    name: 'MediaFileSettings',
    setup() {
        const mediaFilesStore = useMediaFilesStore();
        const mediaViewerStore = useMediaViewerStore();
        const mediaFile = computed(() => mediaFilesStore.mediaFiles[mediaViewerStore.displayIndex]);

        const settings: MediaSettings = reactive({
            size: 'medium',
            alignment: 'center',
            link: 'media',
            linkUrl: '',
        });

        const alignmentOptions: SelectOption[] = [
            { label: 'Left', value: 'left' },
            { label: 'Center', value: 'center' },
            { label: 'Right', value: 'right' },
            { label: 'None', value: 'none' },
        ];

        const linkOptions: SelectOption[] = [
            { label: 'None', value: 'none' },
            { label: 'Media', value: 'media' },
            { label: 'Media page', value: 'page' },
            { label: 'Custom URL', value: 'custom' },
        ];

        const fileSizesOptions = computed(() => {
            return [
                {
                    label: `Original size - ${mediaFile.value.width} x ${mediaFile.value.height}`,
                    value: 'original',
                },
                ...Object.keys(mediaFile.value.sizes).map((name) => {
                    return {
                        label: `${capitalCase(name)} - ${mediaFile.value.sizes[name].width} x ${
                            mediaFile.value.sizes[name].height
                        }`,
                        value: name,
                    };
                }),
            ];
        });

        onMounted(() => {
            mediaViewerStore.settings = settings;
        });

        watch(
            settings,
            () => {
                mediaViewerStore.settings = settings;
            },
            { deep: true },
        );

        return {
            mediaFile,
            settings,
            alignmentOptions,
            linkOptions,
            fileSizesOptions,
        };
    },
});
</script>
