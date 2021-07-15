<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div class="flex flex-col">
        <label class="font-semibold text-quaternary">{{ parameters.label }}</label>
        <select :value="value" @change="updateMenu">
            <option v-for="menu in menus" :key="menu.id" :value="menu.id">
                {{ menu.name }}
            </option>
        </select>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, PropType } from 'vue';
import FormControlParameters from 'admin/interfaces/FormControlParameters';
import { useMenuStore } from 'admin/vue/stores/menu';

export default defineComponent({
    name: 'FormControlMenu',
    props: {
        value: {
            type: [String, Number],
            required: true,
        },
        parameters: {
            type: Object as PropType<FormControlParameters>,
            required: true,
        },
    },
    emits: ['input', 'input-computed'],
    setup(props, { emit }) {
        const menuStore = useMenuStore();
        const menus = computed(() => menuStore.menus);

        onMounted(async () => {
            await menuStore.fetchMenus();

            if (props.value) {
                const menu = menuStore.menus.find(
                    (m) => m.id === (typeof props.value === 'string' ? parseInt(props.value) : props.value),
                );
                emit('input-computed', menu);
            }
        });

        function updateMenu(event: Event): void {
            const menu = menuStore.menus.find((m) => m.id === parseInt((event.target as HTMLSelectElement)!.value));
            emit('input', (event.target as HTMLSelectElement)!.value);
            emit('input-computed', menu);
        }

        return {
            menus,
            updateMenu,
        };
    },
});
</script>
