<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <component
        :is="componentName"
        v-if="isVisible"
        ref="elementRef"
        :self-instance="component"
        :parameters="component.parameters"
        :responsive="component.responsive"
        :computed="component.computed"
        :view-size="viewSize"
        :children="component.children"
        :data-component-id="component.id"
        class="n9-page-builder-component"
    />
</template>

<script lang="ts">
import { computed, defineComponent, onBeforeUnmount, onMounted, reactive, ref, Ref, watch } from 'vue';
import { usePageBuilderStore } from 'admin/vue/stores/pageBuilder';
import { pascalCase } from 'change-case';
import PageComponent from 'admin/interfaces/PageComponent';
import { eventBus } from 'admin/admin';
import {
    EVENT_PAGE_BUILDER_COMPONENT_SELECTED,
    EVENT_PAGE_BUILDER_REQUEST_FOR_EDIT_COMPONENT,
} from 'admin/events/events';
import usePageBuilderHelpers from 'admin/vue/functions/pageBuilderHelpers';

export default defineComponent({
    name: 'PageBuilderComponent',
    props: {
        component: {
            type: Object as () => PageComponent,
            required: true,
        },
    },
    setup(props) {
        const pageBuilderStore = usePageBuilderStore();
        const { preloadComponent } = usePageBuilderHelpers();
        const viewSize = 'lg';
        const componentName = computed(() => `${pascalCase(props.component.name)}PageBuilderComponent`);
        const isVisible = true;
        const elementRef: Ref<{ $el: HTMLElement } | null> = ref(null);
        const mouse = reactive({ down: false, move: false });

        onMounted(() => {
            preloadComponent(props.component);

            elementRef.value!.$el.addEventListener('mouseover', highlight);
            elementRef.value!.$el.addEventListener('mousedown', mouseDown);
            elementRef.value!.$el.addEventListener('mouseup', mouseUp);
            elementRef.value!.$el.addEventListener('mousemove', mouseMove);
            elementRef.value!.$el.addEventListener('click', select);
            elementRef.value!.$el.addEventListener('dblclick', edit);
        });

        onBeforeUnmount(() => {
            elementRef.value!.$el.removeEventListener('mouseover', highlight);
            elementRef.value!.$el.removeEventListener('mousedown', mouseDown);
            elementRef.value!.$el.removeEventListener('mouseup', mouseUp);
            elementRef.value!.$el.removeEventListener('mousemove', mouseMove);
            elementRef.value!.$el.removeEventListener('click', select);
            elementRef.value!.$el.removeEventListener('dblclick', edit);
        });

        function highlight(event: MouseEvent) {
            event.stopPropagation();
            pageBuilderStore.highlightedId = props.component.id;
        }

        function select(event: MouseEvent) {
            event.preventDefault();
            event.stopPropagation();
            pageBuilderStore.selectedId = props.component.id;

            eventBus.emit(EVENT_PAGE_BUILDER_COMPONENT_SELECTED, {
                component: pageBuilderStore.selectedComponent!,
            });
        }

        function edit(event: MouseEvent) {
            event.preventDefault();
            event.stopPropagation();

            eventBus.emit(EVENT_PAGE_BUILDER_REQUEST_FOR_EDIT_COMPONENT, {
                component: pageBuilderStore.selectedComponent!,
            });
        }

        function mouseDown(event: MouseEvent) {
            event.stopPropagation();
            mouse.down = true;
            pageBuilderStore.isContextMenuVisible = false;
        }

        function mouseMove() {
            mouse.move = !!mouse.down;
        }

        function mouseUp() {
            mouse.down = false;
            mouse.move = false;
        }

        const isDragging = computed(() => mouse.down && mouse.move);

        watch(isDragging, () => (pageBuilderStore.dragId = isDragging.value ? props.component.id : undefined));

        return {
            elementRef,
            componentName,
            viewSize,
            isVisible,
        };
    },
});
</script>

<style scoped></style>
