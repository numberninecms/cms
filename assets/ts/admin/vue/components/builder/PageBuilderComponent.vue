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
        :self-instance="component"
        :parameters="component.parameters"
        :responsive="component.responsive"
        :computed="component.computed"
        :view-size="viewSize"
        :children="component.children"
        :data-component-id="component.id"
        class="n9-page-builder-component"
        @mousemove="highlight"
        @mouseleave="removeHighlight"
    />
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, watch } from 'vue';
import PageComponent from '../../../interfaces/PageComponent';
import { usePageBuilderStore } from 'admin/vue/stores/pageBuilder';

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
        const viewSize = 'lg';
        const componentName = computed(() => `${props.component.name}PageBuilderComponent`);
        const isVisible = true;
        let element: HTMLElement;

        onMounted(() => {
            element = pageBuilderStore.document.querySelector(`[data-component-id='${props.component.id}']`)!;
            element.style.transition = 'transform .2s ease-in-out';
        });

        function highlight() {
            pageBuilderStore.highlightedId = props.component.id;
        }

        function removeHighlight() {
            pageBuilderStore.highlightedId = undefined;
        }

        watch(
            () => pageBuilderStore.highlightedId,
            () => {
                element.style.transform = `perspective(100px) translateZ(${
                    pageBuilderStore.highlightedId ? '-3' : '0'
                }px)`;
            },
        );

        return {
            componentName,
            viewSize,
            isVisible,
            highlight,
            removeHighlight,
        };
    },
});
</script>

<style scoped></style>
