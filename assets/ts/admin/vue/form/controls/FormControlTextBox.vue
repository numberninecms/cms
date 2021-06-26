<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div class="flex flex-col px-3 mt-2">
        <label class="font-semibold text-quaternary">{{ parameters.label }}</label>
        <input type="text" :value="value" @input="$emit('input', $event.target.value)" />
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import FormControlParameters from 'admin/interfaces/FormControlParameters';

export default defineComponent({
    name: 'FormControlTextBox',
    props: {
        value: {
            type: String,
            required: true,
        },
        parameters: {
            type: Object as () => FormControlParameters,
            required: true,
        },
    },
    emits: ['input'],
});
</script>
