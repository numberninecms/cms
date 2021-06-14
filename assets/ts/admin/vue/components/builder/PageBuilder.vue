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
        <PageBuilderComponent v-for="component in components" :key="component.id" :component="component" />
    </div>
</template>
<script lang="ts">
import { computed, defineComponent } from 'vue';
import { usePageBuilderStore } from 'admin/vue/stores/pageBuilder';
import PageBuilderComponent from 'admin/vue/components/builder/PageBuilderComponent.vue';

export default defineComponent({
    name: 'PageBuilder',
    components: { PageBuilderComponent },
    setup() {
        const store = usePageBuilderStore();

        return {
            components: computed(() => store.pageComponents),
        };
    },
});
</script>
