<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div class="flex-grow overflow-y-auto">
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
</template>

<script lang="ts">
import { defineComponent, Ref, ref, watchEffect } from 'vue';
import MediaFile from 'admin/interfaces/MediaFile';
import useStringHelpers from 'admin/vue/functions/stringHelpers';
import GenericObject from 'admin/interfaces/GenericObject';
import dateFormat from 'dateformat';
import * as math from 'mathjs';

export default defineComponent({
    name: 'MediaFileProperties',
    props: {
        mediaFile: {
            type: Object as () => MediaFile,
            required: true,
        },
    },
    setup(props) {
        const { readableBytes } = useStringHelpers();
        const properties: Ref<GenericObject<{ value: any; link?: boolean }>[]> = ref([]);

        watchEffect(() => {
            properties.value = [
                {
                    Name: { value: props.mediaFile.slug },
                    Type: { value: props.mediaFile.mimeType},
                    Size: { value: readableBytes(props.mediaFile.fileSize) },
                    Dimensions: { value: `${props.mediaFile.width} x ${props.mediaFile.height} pixels` },
                },
                {
                    Date: { value: dateFormat(props.mediaFile.createdAt, 'longDate') },
                    Time: { value: dateFormat(props.mediaFile.createdAt, 'isoTime') },
                },
                {
                    URL: {
                        value: `${window.location.origin}${props.mediaFile.path}`,
                        link: true,
                    },
                },
            ];

            if (props.mediaFile.mimeType.startsWith('image/') && props.mediaFile.exif) {
                const exif = {};

                if (props.mediaFile.exif['computed.ApertureFNumber']) {
                    exif['Aperture'] = { value: props.mediaFile.exif['computed.ApertureFNumber'] };
                }

                if (props.mediaFile.exif['exif.ExposureTime']) {
                    exif['Exposure'] = { value: props.mediaFile.exif['exif.ExposureTime'] };
                }

                if (props.mediaFile.exif['exif.FocalLength']) {
                    exif['Focal length'] = { value: math.evaluate(props.mediaFile.exif['exif.FocalLength']) };
                }

                if (props.mediaFile.exif['exif.ISOSpeedRatings']) {
                    exif['ISO'] = { value: props.mediaFile.exif['exif.ISOSpeedRatings'] };
                }

                if (props.mediaFile.exif['exif.MeteringMode']) {
                    exif['Metering'] = { value: props.mediaFile.exif['exif.MeteringMode'] };
                }

                if (props.mediaFile.exif['ifd0.Model']) {
                    exif['Camera'] = { value: props.mediaFile.exif['ifd0.Model'] };
                }

                properties.value.push(exif);
            }
        });

        return {
            properties,
        };
    },
});
</script>
