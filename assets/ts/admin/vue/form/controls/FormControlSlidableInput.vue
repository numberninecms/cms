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
        <SlidableInput
            :modelValue="value"
            :min="parameters.min"
            :max="parameters.max"
            :suffix="parameters.suffix"
            @update:modelValue="$emit('input', $event)"
        />
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import SlidableInput from 'admin/vue/form/SlidableInput.vue';
import FormControlParameters from 'admin/interfaces/FormControlParameters';

export default defineComponent({
    name: 'FormControlSlidableInput',
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
