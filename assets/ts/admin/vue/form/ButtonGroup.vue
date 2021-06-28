<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div class="btn-group" role="group" aria-label="Button group">
        <button
            v-for="(option, index) in options"
            :key="option.value"
            :title="option.label"
            class="btn flex-grow justify-center items-center"
            :class="{
                'rounded-l-lg': index === 0,
                'rounded-r-lg': index === options.length - 1,
                'btn-color-white': option.value !== modelValue,
                'btn-color-primary': option.value === modelValue,
            }"
            @click="$emit('update:modelValue', option.value)"
        >
            <slot :name="option.value">
                {{ option.label }}
            </slot>
        </button>
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import SelectOption from 'admin/interfaces/SelectOption';

export default defineComponent({
    name: 'ButtonGroup',
    props: {
        modelValue: {
            type: String,
            required: true,
        },
        options: {
            type: Array as PropType<SelectOption[]>,
            required: true,
        },
    },
    emits: ['update:modelValue'],
});
</script>
