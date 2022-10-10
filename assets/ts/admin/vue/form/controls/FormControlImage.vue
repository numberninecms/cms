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
        <MediaSelect
            :modelValue="model"
            :find-by="findBy"
            class="w-full"
            @update:model-value="$emit('input', $event)"
            @input-computed="$emit('input-computed', $event)"
        />
    </div>
</template>

<script lang="ts">
import { defineComponent, PropType, ref } from 'vue';
import FormControlParameters from 'admin/interfaces/FormControlParameters';
import MediaSelect from 'admin/vue/form/MediaSelect.vue';
import PageComponent from 'admin/interfaces/PageComponent';
import FormControlCriteria from 'admin/interfaces/FormControlCriteria';

export default defineComponent({
    name: 'FormControlImage',
    components: { MediaSelect },
    props: {
        value: {
            type: [String, Number],
            required: true,
        },
        parameters: {
            type: Object as PropType<FormControlParameters>,
            required: true,
        },
        component: {
            type: Object as PropType<PageComponent>,
            required: true,
        },
    },
    emits: ['input', 'input-computed'],
    setup(props) {
        const model = ref(props.value);
        const findBy = ref('id');

        if (!props.value && Object.prototype.hasOwnProperty.call(props.parameters, 'fallback_criteria')) {
            const criteria = props.parameters['fallback_criteria'] as FormControlCriteria;
            model.value = props.component.parameters[criteria.valueFrom] as string;
            findBy.value = criteria.findBy;
        }

        return {
            model,
            findBy,
        };
    },
});
</script>
