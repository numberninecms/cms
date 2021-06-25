<template>
    <sortable-tree-node
        :nodes="nodes"
        :node-key="nodeKey"
        :label-key="labelKey"
        :children-key="childrenKey"
        :leaf-key="leafKey"
        :default-expand-all="defaultExpandAll"
        class="sortable-tree"
        :class="{ 'q-tree--no-connectors': noConnectors }"
        :selected="selected"
        :drag-area-height="dragAreaHeight"
        @select="$emit('select', $event)"
        @dblclick="$emit('dblclick', $event)"
        @rightclick="$emit('rightclick', $event)"
        @mouseenter="$emit('mouseenter', $event)"
        @mouseover="$emit('mouseover', $event)"
        @mouseleave="$emit('mouseleave', $event)"
        @update:nodes="$emit('update:nodes', nodes)"
        @change="$emit('change', nodes)"
    >
        <template #default-header="props">
            <slot name="default-header" v-bind="props"></slot>
        </template>
        <template #default-body="props">
            <slot name="default-body" v-bind="props"></slot>
        </template>
    </sortable-tree-node>
</template>
<script lang="ts">
import { defineComponent, onMounted, onUpdated } from 'vue';
import SortableTreeNode from 'admin/vue/components/tree/SortableTreeNode.vue';
import TreeNode from 'admin/interfaces/TreeNode';

export default defineComponent({
    name: 'SortableTree',
    components: { SortableTreeNode },
    props: {
        nodes: {
            required: true,
            type: Array as () => TreeNode[],
        },
        nodeKey: {
            required: true,
            type: String,
        },
        labelKey: {
            required: false,
            type: String,
            default: 'label',
        },
        childrenKey: {
            required: false,
            type: String,
            default: 'children',
        },
        leafKey: {
            required: false,
            type: String,
            default: 'leaf',
        },
        defaultExpandAll: {
            required: false,
            type: Boolean,
            default: false,
        },
        noConnectors: {
            required: false,
            type: Boolean,
            default: false,
        },
        selected: {
            required: false,
            type: String,
            default: null,
        },
        dragAreaHeight: {
            required: false,
            type: Number,
            default: 1,
        },
    },
    emits: ['select', 'dblclick', 'rightclick', 'mouseenter', 'mouseover', 'mouseleave', 'update:nodes', 'change'],
    setup(props) {
        onMounted(() => {
            repairNodes(props.nodes);
        });

        onUpdated(() => {
            repairNodes(props.nodes);
        });

        function repairNodes(nodes: TreeNode[]) {
            nodes.forEach((node) => {
                if (!Object.hasOwnProperty.call(node, props.childrenKey) || !Array.isArray(node[props.childrenKey])) {
                    node[props.childrenKey] = [];
                } else {
                    repairNodes(node[props.childrenKey]);
                }

                if (!Object.hasOwnProperty.call(node, 'collapsed')) {
                    if (node.disabled) {
                        node.collapsed = true;
                    } else {
                        node.collapsed = isLeaf(node) ? false : !props.defaultExpandAll;
                    }
                }
            });
        }

        function isLeaf(node: TreeNode): boolean {
            return !(
                Object.hasOwnProperty.call(node, props.childrenKey) &&
                (node[props.childrenKey] as TreeNode[]).length > 0
            );
        }
    },
});
</script>
