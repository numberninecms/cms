<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div class="flex flex-col">
        <label class="font-semibold text-quaternary">{{ parameters.label }}</label>
        <div class="flex flex-grow input-group">
            <input
                class="flex-grow rounded-l"
                type="text"
                :value="value"
                @input="$emit('input', $event.target.value)"
            />
            <button
                type="button"
                class="btn rounded-r-lg"
                :class="{
                    'bg-primary': !model,
                }"
                :style="{
                    'background-color': model,
                }"
                @click="showColorPicker"
            >
                <i class="fa fa-eye-dropper"></i>
            </button>
        </div>
        <ColorPicker
            v-if="isColorPickerVisible"
            v-model="model"
            :position="position"
            @update:model-value="$emit('input', $event)"
            @close="isColorPickerVisible = false"
        />
    </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, PropType, reactive, ref } from 'vue';
import FormControlParameters from 'admin/interfaces/FormControlParameters';
import ColorPicker from 'admin/vue/form/ColorPicker.vue';

export default defineComponent({
    name: 'FormControlColor',
    components: { ColorPicker },
    props: {
        value: {
            type: [String, Array],
            required: true,
        },
        parameters: {
            type: Object as PropType<FormControlParameters>,
            required: true,
        },
    },
    emits: ['input'],
    setup(props) {
        const model = ref('');
        const isColorPickerVisible = ref(false);
        const position = reactive({ x: 0, y: 0 });

        onMounted(() => {
            model.value = Array.isArray(props.value) ? '' : props.value;
        });

        function showColorPicker(event: MouseEvent) {
            position.x = event.clientX;
            position.y = event.clientY;
            isColorPickerVisible.value = true;
        }

        return {
            model,
            position,
            isColorPickerVisible,
            showColorPicker,
        };
    },
});
</script>
