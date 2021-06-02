<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div v-if="visible" :class="'flash-' + label" class="z-20">
        <div class="py-3 px-3">
            <div class="flex items-center justify-between flex-wrap">
                <p class="font-medium text-white">
                    {{ message }}
                </p>
                <button
                    type="button"
                    class="-mr-1 flex p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-white text-white"
                    @click="dismiss"
                >
                    <span class="sr-only">Dismiss</span>
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, watchEffect } from 'vue';
import { useFlashesStore } from 'admin/vue/stores/flashes';

export default defineComponent({
    name: 'FlashBar',
    props: {
        delay: {
            type: Number,
            default: 3000,
        },
    },
    emits: ['update:visible'],
    setup(props) {
        const flashesStore = useFlashesStore();

        watchEffect(() => {
            if (flashesStore.visible) {
                setTimeout(() => {
                    dismiss();
                }, props.delay);
            }
        });

        const dismiss = () => {
            flashesStore.visible = false;
        };

        return {
            visible: computed(() => flashesStore.visible),
            label: computed(() => flashesStore.label),
            message: computed(() => flashesStore.message),
            dismiss,
        };
    },
});
</script>
