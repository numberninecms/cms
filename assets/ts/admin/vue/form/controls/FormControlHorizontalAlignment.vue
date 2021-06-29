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
        <ButtonGroup
            class="flex-grow"
            :modelValue="value"
            :options="[
                { value: 'start', label: 'Align left' },
                { value: 'center', label: 'Align center' },
                { value: 'end', label: 'Align right' },
                { value: 'between', label: 'Align between' },
                { value: 'around', label: 'Align around' },
            ]"
            @update:modelValue="$emit('input', $event)"
        >
            <template #start>
                <i class="mdi mdi-24px mdi-align-horizontal-left"></i>
            </template>
            <template #center>
                <i class="mdi mdi-24px mdi-align-horizontal-center"></i>
            </template>
            <template #end>
                <i class="mdi mdi-24px mdi-align-horizontal-right"></i>
            </template>
            <template #between>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="h-6">
                    <path fill="currentColor" d="M15,0h1v16h-1V0z M0,0h1v16H0V0z" />
                    <path fill="currentColor" d="M10.5,4.5h3v7h-3V4.5z M2.5,4.5h3v7h-3V4.5z" />
                </svg>
            </template>
            <template #around>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="h-6">
                    <path fill="currentColor" d="M15,0h1v16h-1V0z M0,0h1v16H0V0z" />
                    <path fill="currentColor" d="M9.5,4.5h3v7h-3V4.5z M3.5,4.5h3v7h-3V4.5z" />
                </svg>
            </template>
        </ButtonGroup>
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import FormControlParameters from 'admin/interfaces/FormControlParameters';
import ButtonGroup from 'admin/vue/form/ButtonGroup.vue';
import { JustifyAlignment } from 'admin/types/JustifyAlignment';

export default defineComponent({
    name: 'FormControlHorizontalAlignment',
    components: { ButtonGroup },
    props: {
        value: {
            type: String as PropType<JustifyAlignment>,
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
