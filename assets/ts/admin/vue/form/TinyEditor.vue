<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <Editor v-model="model" :init="init" />
</template>

<script lang="ts">
import { defineComponent } from 'vue';

import 'tinymce';
import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/skins/ui/oxide/skin.css';
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/autoresize';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/imagetools';
import 'tinymce/plugins/charmap';
import 'tinymce/plugins/print';
import 'tinymce/plugins/preview';
import 'tinymce/plugins/anchor';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/visualblocks';
import 'tinymce/plugins/code';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/insertdatetime';
import 'tinymce/plugins/media';
import 'tinymce/plugins/table';
import 'tinymce/plugins/paste';
import 'tinymce/plugins/help';
import 'tinymce/plugins/wordcount';
import 'admin/tinymce/plugins/medialibrary';

import Editor from '@tinymce/tinymce-vue';
import createTinymceOptions from 'admin/tinymce/createTinymceOptions';
import { useApiStore } from 'admin/vue/stores/api';
import { useVModel } from '@vueuse/core';

export default defineComponent({
    name: 'TinyEditor',
    components: { Editor },
    props: {
        modelValue: {
            type: String,
            required: true,
        },
    },
    setup(props, { emit }) {
        const api = useApiStore();

        return {
            model: useVModel(props, 'modelValue', emit),
            init: createTinymceOptions({ css: api.frontendCssUrl }),
        };
    },
});
</script>
