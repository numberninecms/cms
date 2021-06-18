<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div id="n9-page-builder-tool-insert-button" ref="wrapperRef" :style="styles">
        <button
            v-show="active"
            ref="buttonRef"
            :class="dropPosition ? 'n9-' + dropPosition : null"
            :style="buttonStyles"
        >
            <i class="fas fa-plus fa-xs"></i>
        </button>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, onBeforeUnmount, onMounted, Ref, ref, watch } from 'vue';
import GenericObject from 'admin/interfaces/GenericObject';
import { useMouseStore } from 'admin/vue/stores/mouse';
import { usePageBuilderStore } from 'admin/vue/stores/pageBuilder';
import PageBuilderAddElementEvent from 'admin/events/PageBuilderAddElementEvent';
import { EVENT_PAGE_BUILDER_ADD_ELEMENT } from 'admin/events/events';
import { eventBus } from 'admin/admin';

export default defineComponent({
    name: 'PageBuilderToolInsertButton',
    setup() {
        const mouseStore = useMouseStore();
        const pageBuilderStore = usePageBuilderStore();
        const wrapperRef: Ref<HTMLDivElement | null> = ref(null);
        const buttonRef: Ref<HTMLButtonElement | null> = ref(null);
        const active = computed(() => mouseStore.over && !!pageBuilderStore.dropPosition);

        onMounted(() => {
            buttonRef.value?.addEventListener('click', addToContent);
        });

        onBeforeUnmount(() => {
            buttonRef.value?.removeEventListener('click', addToContent);
        });

        const styles = computed(() => {
            const styles: GenericObject<string> = {};
            const element = pageBuilderStore.highlightedComponentElement;

            if (!element) {
                return styles;
            }

            const rect = element.getBoundingClientRect();
            const scrollLeft = pageBuilderStore.document.documentElement.scrollLeft;
            const scrollTop = pageBuilderStore.document.documentElement.scrollTop;

            styles.width = `${rect.right - rect.left}px`;
            styles.height = `${rect.bottom - rect.top}px`;
            styles.transform = `translateX(${rect.left + scrollLeft}px) translateY(${rect.top + scrollTop}px)`;

            return styles;
        });

        const buttonStyles = computed(() => {
            const styles: GenericObject<string> = {};
            const element = pageBuilderStore.highlightedComponentElement;

            if (!element) {
                return styles;
            }

            const rect = element.getBoundingClientRect();
            styles.left = `${mouseStore.x - rect.left}px`;

            return styles;
        });

        watch(
            () => mouseStore.$state,
            () => {
                const rect = pageBuilderStore.highlightedComponentElement?.getBoundingClientRect();

                if (active.value && rect && !mouseStore.isWithinBoundingBox(rect)) {
                    pageBuilderStore.highlightedId = undefined;
                }
            },
            { deep: true },
        );

        function addToContent() {
            eventBus.emit<PageBuilderAddElementEvent>(EVENT_PAGE_BUILDER_ADD_ELEMENT, {
                target: pageBuilderStore.highlightedComponent,
                position: pageBuilderStore.dropPosition,
            });
        }

        return {
            wrapperRef,
            buttonRef,
            styles,
            buttonStyles,
            dropPosition: computed(() => pageBuilderStore.dropPosition),
            active,
        };
    },
});
</script>
