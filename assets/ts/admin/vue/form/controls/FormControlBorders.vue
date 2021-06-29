<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div v-if="model.data" class="flex flex-col">
        <label class="font-semibold text-quaternary">{{ parameters.label }}</label>
        <div class="grid grid-cols-4 gap-2 items-stretch">
            <div v-if="hasBorder('left')" class="col-span-1 flex">
                <SlidableInput
                    v-model="model.data.left"
                    class="items-stretch"
                    border="left"
                    @update:modelValue="updateValue"
                />
            </div>
            <div v-if="hasBorder('top')" class="col-span-1 flex">
                <SlidableInput
                    v-model="model.data.top"
                    class="items-stretch"
                    border="top"
                    @update:modelValue="updateValue"
                />
            </div>
            <div v-if="hasBorder('right')" class="col-span-1 flex">
                <SlidableInput
                    v-model="model.data.right"
                    class="items-stretch"
                    border="right"
                    @update:modelValue="updateValue"
                />
            </div>
            <div v-if="hasBorder('bottom')" class="col-span-1 flex">
                <SlidableInput
                    v-model="model.data.bottom"
                    class="items-stretch"
                    border="bottom"
                    @update:modelValue="updateValue"
                />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, onBeforeUpdate, onMounted, PropType, reactive, Ref, ref } from 'vue';
import FormControlParameters from 'admin/interfaces/FormControlParameters';
import { Border } from 'admin/types/Border';
import SlidableInput from 'admin/vue/form/SlidableInput.vue';
import Borders from 'admin/interfaces/Borders';

export default defineComponent({
    name: 'FormControlBorders',
    components: { SlidableInput },
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
    setup(props, { emit }) {
        const model: { data: Borders | null } = reactive({ data: null });

        onMounted(() => {
            model.data = values.value;
        });

        onBeforeUpdate(() => {
            model.data = values.value;
        });

        const values = computed((): Borders => {
            const array = props.value
                ? props.value.split(' ').map((value) => {
                      const v = value.replace('px', '');
                      return v !== 'auto' ? parseInt(v) : v;
                  })
                : [];

            return {
                top: array.length > 0 ? array[0] : 0,
                right: array.length > 1 ? array[1] : array.length > 0 ? array[0] : 0,
                bottom: array.length > 2 ? array[2] : array.length > 0 ? array[0] : 0,
                left: array.length > 3 ? array[3] : array.length > 1 ? array[1] : array.length > 0 ? array[0] : 0,
            };
        });

        const formattedModel = computed(() => {
            if (!model.data) {
                return '';
            }

            const values = {
                top: hasBorder('top') ? `${model.data.top}px` : 'auto',
                right: hasBorder('right') ? `${model.data.right}px` : 'auto',
                bottom: hasBorder('bottom') ? `${model.data.bottom}px` : 'auto',
                left: hasBorder('left') ? `${model.data.left}px` : 'auto',
            };

            return `${values.top} ${values.right} ${values.bottom} ${values.left}`
                .replace(/\b0px/g, '0')
                .replace(/^0 0$/, '0')
                .replace(/^0 0 0 0$/, '0');
        });

        function hasBorder(border: Border): boolean {
            return (props.parameters.borders as Border[]).indexOf(border) !== -1;
        }

        function updateValue(): void {
            emit('input', formattedModel.value);
        }

        return {
            model,
            hasBorder,
            updateValue,
        };
    },
});
</script>
