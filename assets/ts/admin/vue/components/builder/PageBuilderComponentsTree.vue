<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div class="p-3 flex flex-col gap-3">
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
        <div>
            <button class="btn btn-color-primary" @click="addToContent">
                <i class="mdi mdi-24px mdi-view-grid-plus"></i> Add to content
            </button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, Ref, ref } from 'vue';
import SortableTree from 'admin/vue/components/tree/SortableTree.vue';
import { eventBus } from 'admin/admin';
import {
    EVENT_PAGE_BUILDER_COMPONENT_SELECTED,
    EVENT_PAGE_BUILDER_COMPONENTS_TREE_CHANGED,
    EVENT_PAGE_BUILDER_REQUEST_FOR_ADD_TO_CONTENT,
    EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_COMPONENTS_TREE,
    EVENT_PAGE_BUILDER_REQUEST_FOR_EDIT_COMPONENT,
    EVENT_PAGE_BUILDER_REQUEST_FOR_HIGHLIGHT_COMPONENT,
    EVENT_PAGE_BUILDER_REQUEST_FOR_SELECT_COMPONENT,
} from 'admin/events/events';
import PageComponent from 'admin/interfaces/PageComponent';

export default defineComponent({
    name: 'PageBuilderComponentsTree',
    components: { SortableTree },
    setup() {
        const nodes: Ref<PageComponent[]> = ref([]);
        const selectedId: Ref<string | undefined> = ref(undefined);

        onMounted(() => {
            eventBus.on(EVENT_PAGE_BUILDER_COMPONENTS_TREE_CHANGED, (event) => {
                nodes.value = event.tree;
            });

            eventBus.on(EVENT_PAGE_BUILDER_COMPONENT_SELECTED, (event) => {
                selectedId.value = event.component.id;
            });
        });

        function select(component: PageComponent) {
            eventBus.emit(EVENT_PAGE_BUILDER_REQUEST_FOR_SELECT_COMPONENT, {
                component,
            });

            selectedId.value = component.id;
        }

        function highlight(component: PageComponent) {
            eventBus.emit(EVENT_PAGE_BUILDER_REQUEST_FOR_HIGHLIGHT_COMPONENT, {
                component,
            });
        }

        function edit(component: PageComponent) {
            eventBus.emit(EVENT_PAGE_BUILDER_REQUEST_FOR_EDIT_COMPONENT, {
                component,
            });
        }

        function showContextMenu() {}

        function requestTreeUpdate(newNodes: PageComponent[]) {
            eventBus.emit(EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_COMPONENTS_TREE, {
                tree: JSON.parse(JSON.stringify(newNodes)),
            });
        }

        function cleanup() {
            eventBus.emit(EVENT_PAGE_BUILDER_REQUEST_FOR_HIGHLIGHT_COMPONENT, {
                component: undefined,
            });
        }

        function addToContent() {
            eventBus.emit(EVENT_PAGE_BUILDER_REQUEST_FOR_ADD_TO_CONTENT, {
                tree: nodes.value,
                position: 'bottom',
            });
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
            addToContent,
        };
    },
});
</script>
