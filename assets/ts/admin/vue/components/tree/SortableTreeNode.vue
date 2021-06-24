<template>
    <draggable
        :style="{ 'min-height': dragAreaHeight + 'px' }"
        tag="div"
        :list="nodes"
        :item-key="(node) => node[nodeKey]"
        :group="{ name: 'SortableTree' }"
        :animation="200"
    >
        <template #item="{ element }">
            <div
                class="sortable-tree__node relative"
                :class="{
                    'sortable-tree__node--parent': !isLeaf(element),
                    'sortable-tree__node--child': isLeaf(element),
                }"
                @dblclick.stop="$emit('dblclick', element)"
                @click.right.stop.prevent="$emit('rightclick', element)"
                @click.left.stop="$emit('select', element)"
            >
                <div
                    :tabindex="element.disabled ? -1 : 0"
                    :class="{
                        'sortable-tree__node--disabled': element.disabled,
                        'sortable-hoverable sortable-focusable': !element.disabled,
                        'sortable-tree__node--selected': selected === element[nodeKey],
                    }"
                    class="
                        sortable-tree__node-header
                        relative
                        flex flex-row flex-nowrap
                        items-center
                        sortable-tree__node--link
                    "
                    @mouseenter.stop="$emit('mouseenter', element)"
                    @mouseover.stop="$emit('mouseover', element)"
                    @mouseleave.stop="$emit('mouseleave', element)"
                >
                    <div tabindex="-1" class="sortable-focus-helper"></div>
                    <svg
                        v-if="element[childrenKey].length > 0"
                        aria-hidden="true"
                        role="presentation"
                        focusable="false"
                        viewBox="0 0 24 24"
                        class="sortable-tree__arrow mr-2 sortable-icon"
                        :class="{ 'sortable-tree__arrow--rotate': !element.collapsed }"
                        @click.stop="toggleCollapse(element)"
                    >
                        <path d="M8,5.14V19.14L19,12.14L8,5.14Z"></path>
                    </svg>
                    <div class="sortable-tree__node-header-content flex-1 flex flex-row flex-nowrap items-center">
                        <slot name="default-header" :node="element" :label-key="labelKey">
                            <div v-if="element.avatar" class="sortable-avatar mr-3">
                                <div
                                    class="sortable-avatar-content flex flex-row justify-center items-center flex-wrap"
                                >
                                    <img :src="element.avatar" />
                                </div>
                            </div>
                            <i v-if="element.icon" class="mr-3 fas" :class="`fa-${element.icon}`"></i>
                            <div>
                                {{ element[labelKey] }}
                            </div>
                        </slot>
                    </div>
                </div>

                <div v-if="nodeCanHaveChildren(element)" class="sortable-tree__node-collapsible">
                    <div v-if="hasDefaultBodySlot" class="sortable-tree__node-body relative">
                        <slot name="default-body"></slot>
                    </div>
                    <div v-show="!element.collapsed" class="sortable-tree__children">
                        <sortable-tree-node
                            :nodes="element[childrenKey]"
                            :node-key="nodeKey"
                            :label-key="labelKey"
                            :children-key="childrenKey"
                            :leaf-key="leafKey"
                            :parent-node="element"
                            :default-expand-all="!element.collapsed"
                            :selected="selected"
                            :drag-area-height="dragAreaHeight"
                            @dblclick="$emit('dblclick', $event)"
                            @rightclick="$emit('rightclick', $event)"
                            @select="$emit('select', $event)"
                            @mouseenter="$emit('mouseenter', $event)"
                            @mouseover="$emit('mouseover', $event)"
                            @mouseleave="$emit('mouseleave', $event)"
                            @update:nodes="$emit('update:nodes', nodes)"
                        >
                            <template #default-header="props">
                                <slot name="default-header" v-bind="props"></slot>
                            </template>
                            <template #default-body="props">
                                <slot name="default-body" v-bind="props"></slot>
                            </template>
                        </sortable-tree-node>
                    </div>
                </div>
            </div>
        </template>
    </draggable>
</template>
<script lang="ts">
import draggable from 'vuedraggable';
import { computed, defineComponent } from 'vue';
import TreeNode from 'admin/interfaces/TreeNode';

export default defineComponent({
    name: 'SortableTreeNode',
    components: {
        draggable,
    },
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
        parentNode: {
            required: false,
            type: Object,
            default: null,
        },
        defaultExpandAll: {
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
    emits: ['dblclick', 'rightclick', 'select', 'mouseenter', 'mouseover', 'mouseleave', 'update:nodes'],
    setup(props, { emit, slots }) {
        const hasDefaultBodySlot = computed(() => !!slots.default);

        function toggleCollapse(node: TreeNode): void {
            if (!isLeaf(node) && !node.disabled) {
                node.collapsed = !node.collapsed;
            }

            emit('update:nodes', props.nodes);
        }

        function isLeaf(node: TreeNode): boolean {
            return !(
                Object.hasOwnProperty.call(node, props.childrenKey) &&
                (node[props.childrenKey] as TreeNode[]).length > 0
            );
        }

        function nodeCanHaveChildren(node: TreeNode): boolean {
            return (
                !Object.hasOwnProperty.call(node, props.leafKey) ||
                (Object.hasOwnProperty.call(node, props.leafKey) && !node[props.leafKey])
            );
        }

        return {
            toggleCollapse,
            nodeCanHaveChildren,
            isLeaf,
            hasDefaultBodySlot,
        };
    },
});
</script>
