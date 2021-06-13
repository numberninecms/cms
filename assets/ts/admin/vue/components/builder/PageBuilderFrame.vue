<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div class="flex-1 min-h-full flex" :style="{ height: `${frameHeight - 48}px` }">
        <iframe ref="iframe" :src="url" class="flex-1" @load="onLoad"></iframe>
    </div>
</template>

<script lang="ts">
import { defineComponent, Ref, ref } from 'vue';
import PageBuilderApp from 'admin/services/PageBuilderApp';
import { eventBus } from 'admin/admin';
import {
    EVENT_PAGE_BUILDER_CREATED,
    EVENT_PAGE_BUILDER_FRAME_HEIGHT_CHANGED,
    EVENT_PAGE_BUILDER_LOADED,
} from 'admin/events/events';

export default defineComponent({
    name: 'PageBuilderFrame',
    props: {
        url: {
            type: String,
            required: true,
        },
        disableLinks: {
            type: Boolean,
            required: false,
            default: false,
        },
    },
    setup(props) {
        const iframe: Ref<HTMLIFrameElement | null> = ref(null);
        const frameHeight = ref(0);

        const onLoad = () => {
            const pageBuilderElements = iframe.value!.contentDocument!.body.getElementsByTagName('page-builder');

            if (pageBuilderElements.length > 0) {
                const app = new PageBuilderApp(pageBuilderElements[0]);
                eventBus.emit(EVENT_PAGE_BUILDER_CREATED, { app });
            }

            if (props.disableLinks) {
                const anchors = iframe.value!.contentDocument!.body.getElementsByTagName('a');

                Array.from(anchors).forEach((anchor) => {
                    anchor.addEventListener('click', (e) => e.preventDefault());
                });
            }

            // todo: fix resize bug
            eventBus.on(EVENT_PAGE_BUILDER_FRAME_HEIGHT_CHANGED, (height) => {
                frameHeight.value = height as number;
                iframe.value!.height = `${height - 48}`;
            });
            eventBus.emit(EVENT_PAGE_BUILDER_LOADED);
        };

        return {
            iframe,
            frameHeight,
            onLoad,
        };
    },
});
</script>
