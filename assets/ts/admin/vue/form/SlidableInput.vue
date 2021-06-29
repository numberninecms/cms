<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div class="flex">
        <input
            :value="`${modelValue}${suffix ? suffix : ''}`"
            type="text"
            class="slidable-input flex-grow box-border"
            :class="{
                'border-t-4': border === 'top',
                'border-r-4': border === 'right',
                'border-b-4': border === 'bottom',
                'border-l-4': border === 'left',
            }"
            @input="$emit('update:modelValue', $event.target.value.replace(/[^\d.]/g, ''))"
            @dblclick="stopDragging"
            @click="stopDragging"
            @mousedown="mouseDown"
            @mousemove="mouseMove"
        />
        <SlidableInputOverlay :id="id" />
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, PropType, ref } from 'vue';
import SlidableInputOverlay from 'admin/vue/form/SlidableInputOverlay.vue';
import { Border } from 'admin/types/Border';
import {
    EVENT_SLIDABLE_INPUT_POSITION,
    EVENT_SLIDABLE_INPUT_START_DRAGGING,
    EVENT_SLIDABLE_INPUT_STOP_DRAGGING,
} from 'admin/events/events';
import MouseCoordinatesEvent from 'admin/events/MouseCoordinatesEvent';
import { eventBus } from 'admin/admin';
import Point2D from 'admin/interfaces/Point2D';
import { v4 as uuidv4 } from 'uuid';

export default defineComponent({
    name: 'SlidableInput',
    components: { SlidableInputOverlay },
    props: {
        modelValue: {
            type: [String, Number],
            required: true,
        },
        border: {
            type: [String, undefined] as PropType<Border | undefined>,
            default: undefined,
        },
        suffix: {
            type: [String, undefined] as PropType<string | undefined>,
            default: undefined,
        },
        min: {
            type: [Number, undefined] as PropType<number | undefined>,
            default: undefined,
        },
        max: {
            type: [Number, undefined] as PropType<number | undefined>,
            default: undefined,
        },
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        const mouse: { down: boolean; move: boolean } = { down: false, move: false };
        let originalPosition: Point2D | undefined;
        let originalValue = 0;
        const id = ref(uuidv4());

        function mouseDown(event: MouseEvent): void {
            mouse.down = true;
            originalPosition = { x: event.clientX, y: event.clientY };
            originalValue = typeof props.modelValue === 'string' ? parseInt(props.modelValue) : props.modelValue;
            eventBus.on(EVENT_SLIDABLE_INPUT_STOP_DRAGGING, stopDragging);
        }

        function mouseMove(): void {
            if (mouse.down) {
                if (!mouse.move) {
                    mouse.move = true;
                    eventBus.emit(EVENT_SLIDABLE_INPUT_START_DRAGGING, id.value);
                    eventBus.on(EVENT_SLIDABLE_INPUT_POSITION, updatePosition);
                }
            } else {
                mouse.move = false;
            }
        }

        function stopDragging(dragId): void {
            if (id.value === dragId) {
                eventBus.all.delete(EVENT_SLIDABLE_INPUT_STOP_DRAGGING);
                eventBus.all.delete(EVENT_SLIDABLE_INPUT_POSITION);
                mouse.move = mouse.down = false;
                originalValue = 0;
                originalPosition = undefined;
            }
        }

        function updatePosition(event?: MouseCoordinatesEvent): void {
            if (!event || !originalPosition) {
                return;
            }

            let newValue = originalValue + event.x - originalPosition.x;

            if (props.min !== undefined) {
                newValue = Math.max(newValue, props.min);
            }

            if (props.max) {
                newValue = Math.min(newValue, props.max);
            }

            emit('update:modelValue', newValue);
        }

        return {
            id,
            dragging: computed(() => mouse.down && mouse.move),
            mouseDown,
            mouseMove,
            stopDragging,
        };
    },
});
</script>

<style lang="scss" scoped>
.slidable-input {
    @apply w-full;
    cursor: ew-resize;
}
</style>
