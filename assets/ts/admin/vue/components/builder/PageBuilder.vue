<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div @mouseenter="mouseOver = true" @mousemove="mouseOver = true" @mouseleave="mouseOver = false">
        <div>{{ x }}, {{ y }}, {{ over ? 'over' : 'out' }}</div>
        <PageBuilderComponent v-for="component in components" :key="component.id" :component="component" />
        <PageBuilderToolbox />
    </div>
</template>
<script lang="ts">
import { computed, defineComponent, onMounted, ref, watch } from 'vue';
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
        const mouseStore = useMouseStore();
        const pageBuilderStore = usePageBuilderStore();
        const mouseOver = ref(false);

        onMounted(() => {
            eventBus.on<MouseCoordinatesEvent>(EVENT_PAGE_BUILDER_MOUSE_COORDINATES_CHANGED, (event) => {
                mouseStore.x = event!.x;
                mouseStore.y = event!.y;
            });
        });

        watch(mouseOver, () => {
            mouseStore.over = mouseOver.value;
        });

        return {
            components: computed(() => pageBuilderStore.pageComponents),
            over: computed(() => mouseStore.over),
            mouseOver,
            x: computed(() => mouseStore.x),
            y: computed(() => mouseStore.y),
        };
    },
});
</script>
