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
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import PageBuilderToolOutline from 'admin/vue/components/builder/toolbox/PageBuilderToolOutline.vue';

export default defineComponent({
    name: 'PageBuilderToolbox',
    components: { PageBuilderToolOutline },
    setup() {

    },
});
</script>
