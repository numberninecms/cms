<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div class="flex flex-col md:flex-row justify-start items-start gap-2 md:gap-5">
        <label class="resize-checkbox" :class="{ enabled: resizeOptions.enabled }">
            <input v-model="resizeOptions.enabled" type="checkbox" />
            <span>Resize before upload</span>
        </label>
        <div v-if="resizeOptions.enabled" class="resize-options">
            <div>
                <label for="resize-options-width">Max width</label>
                <input id="resize-options-width" v-model.number="resizeOptions.width" type="number" />
            </div>
            <div>
                <label>Max height</label>
                <input v-model.number="resizeOptions.height" type="number" />
            </div>
            <div>
                <label>Format</label>
                <select v-model="resizeOptions.mimeType">
                    <option v-for="(mimeType, i) in mimeTypes" :key="i" :value="mimeType.value">
                        {{ mimeType.label }}
                    </option>
                </select>
            </div>
            <div v-if="resizeOptions.mimeType === 'image/jpeg'" class="quality">
                <label>Quality</label>
                <div class="flex justify-between items-center gap-3">
                    <input v-model="resizeOptions.quality" type="range" :min="0" :max="100" :step="1" />
                    <span>{{ resizeOptions.quality }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import ResizeOptions from 'admin/interfaces/ResizeOptions';
import useModelWrapper from 'admin/vue/functions/modelWrapper';

interface MediaResizeOptionsProps {
    modelValue: ResizeOptions;
}

export default defineComponent({
    name: 'MediaResizeOptions',
    props: {
        modelValue: {
            type: Object as () => ResizeOptions,
            required: true,
        },
    },
    setup(props: MediaResizeOptionsProps, { emit }) {
        const mimeTypes: { label: string; value: any }[] = [
            { value: 'image/jpeg', label: 'JPEG' },
            { value: 'image/png', label: 'PNG' },
        ];

        return {
            mimeTypes,
            resizeOptions: useModelWrapper<MediaResizeOptionsProps, ResizeOptions>(
                props,
                emit,
            ) as unknown as ResizeOptions,
        };
    },
});
</script>

<style lang="scss" scoped>
.resize-checkbox {
    @apply text-sm
        flex items-center
        gap-3 p-3
        bg-white bg-opacity-90
        shadow-md rounded-br-lg
        border border-primary;

    &.enabled {
        @apply border-transparent rounded-none shadow-none bg-transparent;
    }
}

.resize-options {
    @apply text-gray-800 gap-3 mt-0 flex justify-start items-center flex-wrap;

    > div {
        @apply flex flex-col gap-1;
    }

    select,
    input {
        @apply p-1 text-sm;
        width: 70px;
    }

    label {
        @apply text-xs;
    }

    .quality {
        @apply flex-1 md:flex-initial w-auto;

        input {
            @apply w-auto flex-1;
        }

        span {
            @apply text-xs;
        }
    }
}
</style>
