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
    />
</template>

<script lang="ts">
import { computed, defineComponent } from 'vue';
import PageComponent from '../../../interfaces/PageComponent';

export default defineComponent({
    name: 'PageBuilderComponent',
    props: {
        component: {
            type: Object as () => PageComponent,
            required: true,
        },
    },
    setup(props) {
        const viewSize = 'lg';
        const componentName = computed(() => `${props.component.name}PageBuilderComponent`);
        const isVisible = true;

        return {
            componentName,
            viewSize,
            isVisible,
        };
    },
});
</script>

<style scoped></style>
