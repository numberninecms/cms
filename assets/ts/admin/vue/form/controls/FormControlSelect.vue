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
        <select :value="value" @change="$emit('input', $event.target.value)">
            <option v-for="choice in parameters.choices" :key="choice.value" :value="choice.value">
                {{ choice.label }}
            </option>
        </select>
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import FormControlParameters from 'admin/interfaces/FormControlParameters';

export default defineComponent({
    name: 'FormControlSelect',
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
