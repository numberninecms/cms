<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div id="n9-page-builder-wrapper" ref="builder" :class="{ dragging: isDragging }">
        <PageBuilderComponent v-for="component in components" :key="component.id" :component="component" />
        <PageBuilderToolbox />
    </div>
</template>
<script lang="ts">
import { computed, defineComponent, onBeforeUnmount, onMounted, Ref, ref, watch } from 'vue';
import { usePageBuilderStore } from 'admin/vue/stores/pageBuilder';
import PageBuilderComponent from 'admin/vue/components/builder/PageBuilderComponent.vue';
import {
    EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_VIEWPORT_SIZE_EVENT,
    EVENT_PAGE_BUILDER_COMPONENT_DELETED,
    EVENT_PAGE_BUILDER_MOUSE_COORDINATES_CHANGED,
    EVENT_PAGE_BUILDER_REQUEST_FOR_HIGHLIGHT_COMPONENT,
    EVENT_PAGE_BUILDER_REQUEST_FOR_SELECT_COMPONENT, EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_COMPONENTS_TREE,
} from 'admin/events/events';
import { eventBus } from 'admin/admin';
import MouseCoordinatesEvent from 'admin/events/MouseCoordinatesEvent';
import { useMouseStore } from 'admin/vue/stores/mouse';
import PageBuilderToolbox from 'admin/vue/components/builder/toolbox/PageBuilderToolbox.vue';
import PageBuilderComponentDeletedEvent from 'admin/events/PageBuilderComponentDeletedEvent';
import { PageBuilderRequestForChangeViewportSizeEvent } from 'admin/events/PageBuilderRequestForChangeViewportSizeEvent';
import PageBuilderRequestForHighlightComponentEvent from 'admin/events/PageBuilderRequestForHighlightComponentEvent';
import PageBuilderRequestForSelectComponentEvent from 'admin/events/PageBuilderRequestForSelectComponentEvent';
import PageBuilderRequestForChangeComponentsTree from 'admin/events/PageBuilderRequestForChangeComponentsTree';

export default defineComponent({
    name: 'PageBuilder',
    components: { PageBuilderToolbox, PageBuilderComponent },
    setup() {
        const builder: Ref<HTMLDivElement | null> = ref(null);
        const mouseStore = useMouseStore();
        const pageBuilderStore = usePageBuilderStore();
        const mouseOver = ref(false);

        onMounted(() => {
            eventBus.on<MouseCoordinatesEvent>(EVENT_PAGE_BUILDER_MOUSE_COORDINATES_CHANGED, (event) => {
                mouseStore.x = event!.x;
                mouseStore.y = event!.y;
            });

            eventBus.on<PageBuilderComponentDeletedEvent>(EVENT_PAGE_BUILDER_COMPONENT_DELETED, (event) => {
                if (event!.deletedComponent.id === pageBuilderStore.selectedId) {
                    pageBuilderStore.selectedId = undefined;
                }
            });

            eventBus.on<PageBuilderRequestForChangeViewportSizeEvent>(
                EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_VIEWPORT_SIZE_EVENT,
                (size) => (pageBuilderStore.viewportSize = size!),
            );

            eventBus.on<PageBuilderRequestForSelectComponentEvent>(
                EVENT_PAGE_BUILDER_REQUEST_FOR_SELECT_COMPONENT,
                (event) => {
                    pageBuilderStore.selectedId = event?.component?.id;
                },
            );

            eventBus.on<PageBuilderRequestForHighlightComponentEvent>(
                EVENT_PAGE_BUILDER_REQUEST_FOR_HIGHLIGHT_COMPONENT,
                (event) => {
                    pageBuilderStore.highlightedId = event?.component?.id;
                    mouseStore.over = !!event?.component;
                },
            );

            eventBus.on<PageBuilderRequestForChangeComponentsTree>(
                EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_COMPONENTS_TREE,
                (event) => {
                    pageBuilderStore.pageComponents = event?.tree ?? [];
                },
            );

            pageBuilderStore.document.addEventListener('mousedown', () => {
                if (!mouseStore.over) {
                    pageBuilderStore.selectedId = undefined;
                }
            });

            pageBuilderStore.document.addEventListener('mouseup', () => {
                pageBuilderStore.dragId = undefined;
            });

            builder.value!.addEventListener('mouseenter', onMouseEnter);
            builder.value!.addEventListener('mouseleave', onMouseLeave);
        });

        onBeforeUnmount(() => {
            builder.value!.removeEventListener('mouseenter', onMouseEnter);
            builder.value!.removeEventListener('mouseleave', onMouseLeave);
        });

        function onMouseEnter() {
            mouseOver.value = true;
        }

        function onMouseLeave() {
            mouseOver.value = false;
        }

        watch(mouseOver, () => {
            mouseStore.over = mouseOver.value;
        });

        return {
            builder,
            components: computed(() => pageBuilderStore.pageComponents),
            isDragging: computed(() => pageBuilderStore.dragId !== undefined),
        };
    },
});
</script>
