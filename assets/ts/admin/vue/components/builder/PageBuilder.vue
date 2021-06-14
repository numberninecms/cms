<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div>
        <div>{{ x }}, {{ y }}</div>
        <PageBuilderComponent v-for="component in components" :key="component.id" :component="component" />
    </div>
</template>
<script lang="ts">
import { computed, defineComponent, onMounted, ref } from 'vue';
import { usePageBuilderStore } from 'admin/vue/stores/pageBuilder';
import PageBuilderComponent from 'admin/vue/components/builder/PageBuilderComponent.vue';
import { EVENT_PAGE_BUILDER_MOUSE_COORDINATES_CHANGED } from 'admin/events/events';
import { eventBus } from 'admin/admin';
import MouseCoordinatesEvent from 'admin/events/MouseCoordinatesEvent';

export default defineComponent({
    name: 'PageBuilder',
    components: { PageBuilderComponent },
    setup() {
        const pageBuilderStore = usePageBuilderStore();
        const x = ref(0);
        const y = ref(0);

        onMounted(() => {
            eventBus.on<MouseCoordinatesEvent>(EVENT_PAGE_BUILDER_MOUSE_COORDINATES_CHANGED, (event) => {
                x.value = event!.x;
                y.value = event!.y;
            });
        });

        return {
            components: computed(() => pageBuilderStore.pageComponents),
            x,
            y,
        };
    },
});
</script>
