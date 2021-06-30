<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div class="flex items-center">
        <div class="toggle-group">
            <input
                id="toggle"
                type="checkbox"
                name="toggle"
                class="toggle-checkbox"
                :checked="value"
                @change="$emit('input', $event.target.checked)"
            />
            <label for="toggle" class="toggle-label"></label>
        </div>
        <label for="toggle">{{ parameters.label }}</label>
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import FormControlParameters from 'admin/interfaces/FormControlParameters';

export default defineComponent({
    name: 'FormControlTextBox',
    props: {
        value: {
            type: Boolean,
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
