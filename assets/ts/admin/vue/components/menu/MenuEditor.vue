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
        <SortableTree :nodes="tree" node-key="uid" label-key="title" default-expand-all />
    </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, PropType, ref } from 'vue';
import SortableTree from 'admin/vue/components/tree/SortableTree.vue';
import MenuItem from 'admin/interfaces/MenuItem';
import { EVENT_MENU_ADD_ITEMS } from 'admin/events/events';
import { eventBus } from 'admin/admin';

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

        onMounted(() => {
            eventBus.on(EVENT_MENU_ADD_ITEMS, (event) => {
                tree.value.push(...event.items);
            });
        });

        return {
            tree,
        };
    },
});
</script>
