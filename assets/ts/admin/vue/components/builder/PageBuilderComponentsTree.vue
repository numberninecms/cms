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
        <SortableTree
            :nodes="nodes"
            node-key="id"
            default-expand-all
            :selected="selectedId"
            @select="select"
            @dblclick="edit"
            @rightclick="showContextMenu"
            @mouseenter="highlight"
            @mouseleave="cleanup"
            @change="requestTreeUpdate"
        />
    </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, Ref, ref } from 'vue';
import SortableTree from 'admin/vue/components/tree/SortableTree.vue';
import { eventBus } from 'admin/admin';
import {
    EVENT_PAGE_BUILDER_COMPONENT_SELECTED,
    EVENT_PAGE_BUILDER_COMPONENTS_TREE_CHANGED,
    EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_COMPONENTS_TREE,
    EVENT_PAGE_BUILDER_REQUEST_FOR_HIGHLIGHT_COMPONENT,
    EVENT_PAGE_BUILDER_REQUEST_FOR_SELECT_COMPONENT,
} from 'admin/events/events';
import PageBuilderComponentsTreeChangedEvent from 'admin/events/PageBuilderComponentsTreeChangedEvent';
import PageBuilderComponentSelectedEvent from 'admin/events/PageBuilderComponentSelectedEvent';
import PageComponent from 'admin/interfaces/PageComponent';
import PageBuilderRequestForHighlightComponentEvent from 'admin/events/PageBuilderRequestForHighlightComponentEvent';
import PageBuilderRequestForSelectComponentEvent from 'admin/events/PageBuilderRequestForSelectComponentEvent';
import PageBuilderRequestForChangeComponentsTree from 'admin/events/PageBuilderRequestForChangeComponentsTree';

export default defineComponent({
    name: 'PageBuilderComponentsTree',
    components: { SortableTree },
    setup() {
        const nodes: Ref<PageComponent[]> = ref([]);
        const selectedId: Ref<string | undefined> = ref(undefined);

        onMounted(() => {
            eventBus.on<PageBuilderComponentsTreeChangedEvent>(EVENT_PAGE_BUILDER_COMPONENTS_TREE_CHANGED, (event) => {
                nodes.value = event!.tree;
            });

            eventBus.on<PageBuilderComponentSelectedEvent>(EVENT_PAGE_BUILDER_COMPONENT_SELECTED, (event) => {
                selectedId.value = event!.component.id;
            });
        });

        function select(component: PageComponent) {
            eventBus.emit<PageBuilderRequestForSelectComponentEvent>(EVENT_PAGE_BUILDER_REQUEST_FOR_SELECT_COMPONENT, {
                component,
            });

            selectedId.value = component.id;
        }

        function highlight(component: PageComponent) {
            eventBus.emit<PageBuilderRequestForHighlightComponentEvent>(
                EVENT_PAGE_BUILDER_REQUEST_FOR_HIGHLIGHT_COMPONENT,
                {
                    component,
                },
            );
        }

        function edit() {}

        function showContextMenu() {}

        function requestTreeUpdate(nodes: PageComponent[]) {
            eventBus.emit<PageBuilderRequestForChangeComponentsTree>(
                EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_COMPONENTS_TREE,
                {
                    tree: JSON.parse(JSON.stringify(nodes)),
                },
            );
        }

        function cleanup() {
            eventBus.emit<PageBuilderRequestForHighlightComponentEvent>(
                EVENT_PAGE_BUILDER_REQUEST_FOR_HIGHLIGHT_COMPONENT,
                {
                    component: undefined,
                },
            );
        }

        return {
            nodes,
            selectedId,
            select,
            highlight,
            edit,
            showContextMenu,
            cleanup,
            requestTreeUpdate,
        };
    },
});
</script>
