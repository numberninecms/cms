<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div id="page-builder-content-frame" class="w-full min-h-full flex" :style="{ height, width }">
        <iframe ref="iframe" :src="frontendUrl" class="flex-1" @load="onLoad"></iframe>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, Ref, ref } from 'vue';
import PageBuilderApp from 'admin/classes/PageBuilderApp';
import { eventBus } from 'admin/admin';
import {
    EVENT_PAGE_BUILDER_CHANGE_VIEWPORT_SIZE_EVENT,
    EVENT_PAGE_BUILDER_CREATED,
    EVENT_PAGE_BUILDER_FRAME_HEIGHT_CHANGED,
    EVENT_PAGE_BUILDER_LOADED,
    EVENT_PAGE_BUILDER_MOUSE_COORDINATES_CHANGED,
} from 'admin/events/events';
import PageBuilderCreatedEvent from 'admin/events/PageBuilderCreatedEvent';
import PageBuilderLoadedEvent from 'admin/events/PageBuilderLoadedEvent';
import MouseCoordinatesEvent from 'admin/events/MouseCoordinatesEvent';
import { PageBuilderFrameHeightChangedEvent } from 'admin/events/PageBuilderFrameHeightChangedEvent';
import { PageBuilderChangeViewportSizeEvent } from 'admin/events/PageBuilderChangeViewportSizeEvent';

export default defineComponent({
    name: 'PageBuilderFrame',
    props: {
        frontendUrl: {
            type: String,
            required: true,
        },
        componentsApiUrl: {
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
        const width = ref('100%');

        onMounted(() => {
            eventBus.on<PageBuilderChangeViewportSizeEvent>(EVENT_PAGE_BUILDER_CHANGE_VIEWPORT_SIZE_EVENT, (size) => {
                console.log('ahag', size);
                switch (size) {
                    case 'md':
                        width.value = '768px';
                        break;

                    case 'xs':
                        width.value = '425px';
                        break;

                    default:
                        width.value = '100%';
                }
            });
        });

        const onLoad = () => {
            const pageBuilderElements = iframe.value!.contentDocument!.body.getElementsByTagName('page-builder');

            if (pageBuilderElements.length === 0) {
                throw new Error('Page without <page-builder> tag. Aborting.');
            }

            const app = new PageBuilderApp(pageBuilderElements[0], props.componentsApiUrl);
            eventBus.emit<PageBuilderCreatedEvent>(EVENT_PAGE_BUILDER_CREATED, { app });

            if (props.disableLinks) {
                disableFrameLinks();
            }

            iframe.value!.contentWindow!.addEventListener('mousemove', updateMouseCoordinates);

            // todo: fix resize bug
            eventBus.on<PageBuilderFrameHeightChangedEvent>(EVENT_PAGE_BUILDER_FRAME_HEIGHT_CHANGED, (height) => {
                frameHeight.value = height!;
                iframe.value!.height = `${height! - 48}`;
            });
            eventBus.emit<PageBuilderLoadedEvent>(EVENT_PAGE_BUILDER_LOADED);
        };

        function disableFrameLinks(): void {
            const anchors = iframe.value!.contentDocument!.body.getElementsByTagName('a');

            Array.from(anchors).forEach((anchor) => {
                anchor.addEventListener('click', (e) => e.preventDefault());
            });
        }

        function updateMouseCoordinates(event: MouseEvent): void {
            eventBus.emit<MouseCoordinatesEvent>(EVENT_PAGE_BUILDER_MOUSE_COORDINATES_CHANGED, {
                x: event.clientX,
                y: event.clientY,
            });
        }

        return {
            iframe,
            width,
            height: computed(() => `${frameHeight.value - 48}px`),
            onLoad,
        };
    },
});
</script>
