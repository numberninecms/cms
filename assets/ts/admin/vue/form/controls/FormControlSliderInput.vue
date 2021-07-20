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
        <div class="grid grid-cols-4 gap-3 items-center">
            <div class="col-span-3">
                <input
                    class="slider"
                    :value="value"
                    type="range"
                    :min="parameters.min"
                    :max="parameters.max"
                    :step="parameters.step"
                    @input="$emit('input', $event.target.value)"
                />
            </div>
            <div class="col-span-1">
                <SlidableInput
                    class="slider-input"
                    :modelValue="value"
                    :min="parameters.min"
                    :max="parameters.max"
                    :suffix="parameters.suffix"
                    @update:modelValue="$emit('input', $event)"
                />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import FormControlParameters from 'admin/interfaces/FormControlParameters';
import SlidableInput from 'admin/vue/form/SlidableInput.vue';

export default defineComponent({
    name: 'FormControlSliderInput',
    components: { SlidableInput },
    props: {
        value: {
            type: [String, Number],
            required: true,
        },
        parameters: {
            type: Object as PropType<FormControlParameters>,
            required: true,
        },
    },
    emits: ['input'],
});
</script>

<style lang="scss" scoped>
.slider,
.slider-input {
    @apply w-full;
}
</style>
