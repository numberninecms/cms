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
        <TinyEditor :modelValue="value" @update:modelValue="$emit('input', $event)" />
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import FormControlParameters from 'admin/interfaces/FormControlParameters';
import TinyEditor from 'admin/vue/form/TinyEditor.vue';

export default defineComponent({
    name: 'FormControlEditor',
    components: { TinyEditor },
    props: {
        value: {
            type: String,
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
