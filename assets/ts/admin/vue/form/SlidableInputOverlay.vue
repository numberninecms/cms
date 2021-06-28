<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div v-if="dragging" class="invisible-overlay" @mousemove="drag" @mouseup="stopDrag"></div>
</template>

<script lang="ts">
import { defineComponent, onBeforeUnmount, onMounted, ref } from 'vue';
import {
    EVENT_SLIDABLE_INPUT_POSITION,
    EVENT_SLIDABLE_INPUT_START_DRAGGING,
    EVENT_SLIDABLE_INPUT_STOP_DRAGGING,
} from 'admin/events/events';
import { eventBus } from 'admin/admin';

export default defineComponent({
    name: 'SlidableInputOverlay',
    setup() {
        const dragging = ref(false);

        onMounted(() => {
            eventBus.on(EVENT_SLIDABLE_INPUT_START_DRAGGING, () => {
                dragging.value = true;
            });
        });

        onBeforeUnmount(() => {
            eventBus.all.delete(EVENT_SLIDABLE_INPUT_START_DRAGGING);
        });

        function drag(event: MouseEvent) {
            if (dragging.value) {
                eventBus.emit(EVENT_SLIDABLE_INPUT_POSITION, {
                    x: event.clientX,
                    y: event.clientY,
                });
            }
        }

        function stopDrag() {
            eventBus.emit(EVENT_SLIDABLE_INPUT_STOP_DRAGGING);
            dragging.value = false;
        }

        return {
            drag,
            stopDrag,
            dragging,
        };
    },
});
</script>

<style lang="scss" scoped>
.invisible-overlay {
    pointer-events: all;
    cursor: ew-resize;
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 5000;
    margin-left: -100vw;
}
</style>
