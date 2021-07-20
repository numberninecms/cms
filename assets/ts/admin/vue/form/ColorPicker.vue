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
        class="flex flex-col absolute p-3 bg-white box-border border-8 shadow"
        :style="{
            left: `${position.x - 280}px`,
            top: `${position.y - 30}px`,
            'border-color': model,
        }"
    >
        <Sketch v-model="model" :preset-colors="swatches" @update:modelValue="update" />
        <div class="mt-2">
            <button type="button" class="btn btn-color-primary btn-size-xsmall" @click="$emit('close')">OK</button>
        </div>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, onUpdated, PropType, ref } from 'vue';
import { Sketch } from '@ckpack/vue-color';
import { useColorStore } from 'admin/vue/stores/color';
import Point2D from 'admin/interfaces/Point2D';

export default defineComponent({
    name: 'ColorPicker',
    components: { Sketch },
    props: {
        modelValue: {
            type: [String, Array],
            required: true,
        },
        position: {
            type: Object as PropType<Point2D>,
            required: true,
        },
    },
    emits: ['update:modelValue', 'close'],
    setup(props, { emit }) {
        const model = ref('');
        const colorStore = useColorStore();
        const swatches = computed(() =>
            Array.isArray(colorStore.colors) ? colorStore.colors.map((c) => c.value) : [],
        );

        onMounted(() => {
            model.value = Array.isArray(props.modelValue) ? '' : props.modelValue;
        });

        onUpdated(() => {
            model.value = Array.isArray(props.modelValue) ? '' : props.modelValue;
        });

        function update(colors: { hex: string; hex8: string }) {
            emit('update:modelValue', colors.hex8.endsWith('FF') ? colors.hex : colors.hex8);
        }

        return {
            model,
            swatches,
            update,
        };
    },
});
</script>

<style lang="scss" scoped>
.vc-sketch {
    @apply shadow-none border-0 rounded-none p-0;
}
</style>
