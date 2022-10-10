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
                { value: 'left', label: 'Left' },
                { value: 'center', label: 'Center' },
                { value: 'right', label: 'Right' },
            ]"
            @update:model-value="$emit('input', $event)"
        >
            <template #left>
                <i class="mdi mdi-24px mdi-format-align-left"></i>
            </template>
            <template #center>
                <i class="mdi mdi-24px mdi-format-align-center"></i>
            </template>
            <template #right>
                <i class="mdi mdi-24px mdi-format-align-right"></i>
            </template>
        </ButtonGroup>
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import FormControlParameters from 'admin/interfaces/FormControlParameters';
import { TextAlignment } from 'admin/types/TextAlignment';
import ButtonGroup from 'admin/vue/form/ButtonGroup.vue';

export default defineComponent({
    name: 'FormControlTextAlignment',
    components: { ButtonGroup },
    props: {
        value: {
            type: String as PropType<TextAlignment>,
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
