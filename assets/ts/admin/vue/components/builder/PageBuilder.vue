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
import { EVENT_PAGE_BUILDER_MOUSE_COORDINATES_CHANGED } from 'admin/events/events';
import { eventBus } from 'admin/admin';
import MouseCoordinatesEvent from 'admin/events/MouseCoordinatesEvent';
import { useMouseStore } from 'admin/vue/stores/mouse';
import PageBuilderToolbox from 'admin/vue/components/builder/toolbox/PageBuilderToolbox.vue';

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
