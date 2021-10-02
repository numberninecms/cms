<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div class="flex flex-col flex-grow gap-3">
        <div v-if="showThumbnail" class="flex justify-center p-3">
            <img :src="imageUrl(mediaFile, 'preview')" :alt="mediaFile.title" class="object-contain h-48 shadow" />
        </div>
        <div class="flex-grow">
            <div v-for="(group, index) in properties" :key="index" class="flex-grow mb-3">
                <div
                    v-for="(value, key) in group"
                    :key="key"
                    class="flex text-sm sm:flex-col lg:flex-row lg:gap-3 mb-1 lg:mb-0"
                >
                    <div class="flex-shrink-0 text-gray-400 font-semibold lg:text-right lg:w-1/3">{{ key }}</div>
                    <div v-if="value.link">
                        <a :href="value.value" class="break-all text-primary hover:underline">{{ value.value }}</a>
                    </div>
                    <div v-else class="break-all">{{ value.value }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, Ref, ref, watchEffect } from 'vue';
import useStringHelpers from 'admin/vue/functions/stringHelpers';
import GenericObject from 'admin/interfaces/GenericObject';
import dateFormat from 'dateformat';
import * as math from 'mathjs';
import useMediaFileUtilities from 'admin/vue/functions/mediaFileUtilities';
import { useMediaFilesStore } from 'admin/vue/stores/mediaFiles';
import { useMediaViewerStore } from 'admin/vue/stores/mediaViewer';

export default defineComponent({
    name: 'MediaFileProperties',
    props: {
        showThumbnail: {
            type: Boolean,
            required: false,
            default: false,
        },
    },
    setup() {
        const mediaFilesStore = useMediaFilesStore();
        const mediaViewerStore = useMediaViewerStore();
        const { imageUrl } = useMediaFileUtilities();
        const { readableBytes } = useStringHelpers();
        const properties: Ref<GenericObject<{ value: any; link?: boolean }>[]> = ref([]);
        const mediaFile = computed(() => mediaFilesStore.mediaFiles[mediaViewerStore.displayIndex]);

        watchEffect(() => {
            properties.value = [
                {
                    Name: { value: mediaFile.value.slug },
                    Type: { value: mediaFile.value.mimeType},
                    Size: { value: readableBytes(mediaFile.value.fileSize) },
                    Dimensions: { value: `${mediaFile.value.width} x ${mediaFile.value.height} pixels` },
                },
                {
                    Date: { value: dateFormat(mediaFile.value.createdAt as Date, 'longDate') },
                    Time: { value: dateFormat(mediaFile.value.createdAt as Date, 'isoTime') },
                },
                {
                    URL: {
                        value: `${window.location.origin}${mediaFile.value.path}`,
                        link: true,
                    },
                },
            ];

            if (mediaFile.value.mimeType.startsWith('image/') && mediaFile.value.exif) {
                const exif = {};

                if (mediaFile.value.exif['computed.ApertureFNumber']) {
                    exif['Aperture'] = { value: mediaFile.value.exif['computed.ApertureFNumber'] };
                }

                if (mediaFile.value.exif['exif.ExposureTime']) {
                    exif['Exposure'] = { value: mediaFile.value.exif['exif.ExposureTime'] };
                }

                if (mediaFile.value.exif['exif.FocalLength']) {
                    exif['Focal length'] = { value: math.evaluate(mediaFile.value.exif['exif.FocalLength']) };
                }

                if (mediaFile.value.exif['exif.ISOSpeedRatings']) {
                    exif['ISO'] = { value: mediaFile.value.exif['exif.ISOSpeedRatings'] };
                }

                if (mediaFile.value.exif['exif.MeteringMode']) {
                    exif['Metering'] = { value: mediaFile.value.exif['exif.MeteringMode'] };
                }

                if (mediaFile.value.exif['ifd0.Model']) {
                    exif['Camera'] = { value: mediaFile.value.exif['ifd0.Model'] };
                }

                properties.value.push(exif);
            }
        });

        return {
            properties,
            imageUrl,
            mediaFile,
        };
    },
});
</script>
