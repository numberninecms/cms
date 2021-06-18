<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div id="n9-page-builder-toolbox">
        <PageBuilderToolOutline />
        <PageBuilderToolSelect />
        <PageBuilderToolDragHandle />
        <PageBuilderToolInsertLine />
        <PageBuilderToolInsertButton />
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import PageBuilderToolOutline from 'admin/vue/components/builder/toolbox/PageBuilderToolOutline.vue';
import PageBuilderToolSelect from 'admin/vue/components/builder/toolbox/PageBuilderToolSelect.vue';
import PageBuilderToolDragHandle from 'admin/vue/components/builder/toolbox/PageBuilderToolDragHandle.vue';
import PageBuilderToolInsertLine from 'admin/vue/components/builder/toolbox/PageBuilderToolInsertLine.vue';
import PageBuilderToolInsertButton from 'admin/vue/components/builder/toolbox/PageBuilderToolInsertButton.vue';

export default defineComponent({
    name: 'PageBuilderToolbox',
    components: {
        PageBuilderToolInsertButton,
        PageBuilderToolInsertLine,
        PageBuilderToolDragHandle,
        PageBuilderToolOutline,
        PageBuilderToolSelect,
    },
});
</script>
