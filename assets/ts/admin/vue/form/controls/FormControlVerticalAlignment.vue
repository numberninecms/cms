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
                { value: 'start', label: 'Align top' },
                { value: 'center', label: 'Align center' },
                { value: 'end', label: 'Align bottom' },
                { value: 'stretch', label: 'Stretch' },
                { value: 'baseline', label: 'Align baseline' },
            ]"
            @update:model-value="$emit('input', $event)"
        >
            <template #start>
                <i class="mdi mdi-24px mdi-align-vertical-top"></i>
            </template>
            <template #center>
                <i class="mdi mdi-24px mdi-align-vertical-center"></i>
            </template>
            <template #end>
                <i class="mdi mdi-24px mdi-align-vertical-bottom"></i>
            </template>
            <template #stretch>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="h-6">
                    <path fill="currentColor" d="M0,0h16v1H0V0z M0,15h16v1H0V15z" />
                    <path fill="currentColor" d="M3.5,2.5h3v11h-3V2.5z M8.5,2.5h3v11h-3V2.5z" />
                </svg>
            </template>
            <template #baseline>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="h-6">
                    <path fill="currentColor" d="M0,7h16v1H0V7z" />
                    <path
                        fill="currentColor"
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M12,3H8v7h4V3z M11,4H9v3h2V4z M7,3H3v9h4V3z M6,4H4v3h2V4z"
                    />
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
    name: 'FormControlVerticalAlignment',
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
