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
        <div v-if="tree.length === 0">No items.</div>
        <SortableTree
            v-else
            :nodes="tree"
            node-key="uid"
            label-key="title"
            default-expand-all
            @change="updateMenuItems"
        >
            <template #default-header="{ element, labelKey }">
                <div class="flex flex-nowrap flex-grow">
                    <div v-if="element.avatar" class="sortable-avatar mr-2">
                        <div class="sortable-avatar-content flex flex-row justify-center items-center flex-wrap">
                            <img :src="element.avatar" />
                        </div>
                    </div>
                    <i
                        v-if="element.icon"
                        class="mr-2"
                        :class="
                            element.icon.startsWith('mdi')
                                ? `mdi-24px mdi ${element.icon}`
                                : `text-lg fas fa-${element.icon}`
                        "
                    ></i>
                    <div class="flex gap-2 items-center justify-between flex-grow">
                        <span>{{ element[labelKey] }}</span>
                        <button
                            class="btn btn-color-red btn-style-flat btn-size-xsmall"
                            @click="deleteMenuItem(element)"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </template>
        </SortableTree>
    </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, PropType, ref } from 'vue';
import SortableTree from 'admin/vue/components/tree/SortableTree.vue';
import MenuItem from 'admin/interfaces/MenuItem';
import { EVENT_MENU_ADD_ITEMS, EVENT_MENU_ITEMS_UPDATED } from 'admin/events/events';
import { eventBus } from 'admin/admin';
import useMenuHelpers from 'admin/vue/functions/menuHelpers';

export default defineComponent({
    name: 'MenuEditor',
    components: { SortableTree },
    props: {
        items: {
            type: Array as PropType<MenuItem[]>,
            default: () => [] as MenuItem[],
        },
    },
    setup(props) {
        const tree = ref(props.items);
        const { removeItemInTree } = useMenuHelpers();

        onMounted(() => {
            eventBus.on(EVENT_MENU_ADD_ITEMS, (event) => {
                tree.value.push(...event.items);
                updateMenuItems(tree.value);
            });
        });

        function updateMenuItems(newNodes: MenuItem[]): void {
            eventBus.emit(EVENT_MENU_ITEMS_UPDATED, {
                items: JSON.parse(JSON.stringify(newNodes)),
            });
        }

        function deleteMenuItem(menuItem: MenuItem): void {
            removeItemInTree(tree.value, menuItem.uid);
            updateMenuItems(tree.value);
        }

        return {
            tree,
            updateMenuItems,
            deleteMenuItem,
        };
    },
});
</script>
