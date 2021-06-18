<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div v-if="active" id="n9-page-builder-tool-outline" :style="styles">
        <div class="n9-wrapper">
            <h3>{{ label }}</h3>
        </div>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent } from 'vue';
import { usePageBuilderStore } from 'admin/vue/stores/pageBuilder';
import GenericObject from 'admin/interfaces/GenericObject';
import { useMouseStore } from 'admin/vue/stores/mouse';

export default defineComponent({
    name: 'PageBuilderToolOutline',
    setup() {
        const mouseStore = useMouseStore();
        const pageBuilderStore = usePageBuilderStore();

        const styles = computed(() => {
            const styles: GenericObject<string> = {};

            if (!pageBuilderStore.highlightedId) {
                return styles;
            }

            const element = pageBuilderStore.document.querySelector(
                `[data-component-id='${pageBuilderStore.highlightedId}']`,
            )!;

            const rect = element.getBoundingClientRect();
            const scrollLeft = pageBuilderStore.document.documentElement.scrollLeft;
            const scrollTop = pageBuilderStore.document.documentElement.scrollTop;

            styles.width = `${rect.right - rect.left}px`;
            styles.height = `${rect.bottom - rect.top}px`;
            styles.transform = `translateX(${rect.left + scrollLeft}px) translateY(${rect.top + scrollTop}px)`;

            return styles;
        });

        const active = computed(
            () =>
                pageBuilderStore.highlightedId &&
                mouseStore.over &&
                pageBuilderStore.highlightedId !== pageBuilderStore.selectedId,
        );

        return {
            styles,
            active,
            label: computed(() => pageBuilderStore.highlightedComponentLabel),
        };
    },
});
</script>
