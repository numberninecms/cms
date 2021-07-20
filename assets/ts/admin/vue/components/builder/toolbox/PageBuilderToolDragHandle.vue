<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div v-show="active" id="n9-page-builder-tool-drag-handle" :style="styles">
        <h3>
            <span>{{ label }}</span>
        </h3>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent } from 'vue';
import { useMouseStore } from 'admin/vue/stores/mouse';
import { usePageBuilderStore } from 'admin/vue/stores/pageBuilder';

export default defineComponent({
    name: 'PageBuilderToolDragHandle',
    setup() {
        const mouseStore = useMouseStore();
        const pageBuilderStore = usePageBuilderStore();
        const component = computed(() => pageBuilderStore.draggedComponent);

        const styles = computed(() => {
            return { transform: `translate3d(${mouseStore.x}px, ${mouseStore.y}px, 0px)` };
        });

        return {
            styles,
            active: computed(() => pageBuilderStore.dragId !== undefined),
            label: computed(() => component.value?.label ?? ''),
        };
    },
});
</script>
