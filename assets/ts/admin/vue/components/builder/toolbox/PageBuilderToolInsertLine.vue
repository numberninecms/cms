<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div v-show="active" id="n9-page-builder-tool-insert-line" :style="styles">
        <div class="n9-line" :class="position ? 'n9-' + position : null"></div>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, reactive, watch } from 'vue';
import GenericObject from 'admin/interfaces/GenericObject';
import { useMouseStore } from 'admin/vue/stores/mouse';
import { usePageBuilderStore } from 'admin/vue/stores/pageBuilder';
import { DropPosition } from 'admin/types/DropPosition';

export default defineComponent({
    name: 'PageBuilderToolInsertLine',
    setup() {
        const mouseStore = useMouseStore();
        const pageBuilderStore = usePageBuilderStore();
        const lineRect = reactive({ x: 0, y: 0, width: 0, height: 0 });

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

        const toleranceX = computed(() => {
            return Math.min(lineRect.width / 4, 100);
        });

        const toleranceY = computed(() => {
            return Math.min(lineRect.height / 4, 100);
        });

        const position = computed(() => {
            let position: DropPosition | undefined = undefined;

            if (!(pageBuilderStore.draggedComponent && !supportsDraggedComponent.value)) {
                if (Math.abs(lineRect.x - mouseStore.x) < toleranceX.value && supportsHighlightedPosition('left')) {
                    position = 'left';
                }

                if (
                    Math.abs(lineRect.x + lineRect.width - mouseStore.x) < toleranceX.value &&
                    supportsHighlightedPosition('right')
                ) {
                    position = 'right';
                }

                if (Math.abs(lineRect.y - mouseStore.y) < toleranceY.value && supportsHighlightedPosition('top')) {
                    position = 'top';
                }

                if (
                    Math.abs(lineRect.y + lineRect.height - mouseStore.y) < toleranceY.value &&
                    supportsHighlightedPosition('bottom')
                ) {
                    position = 'bottom';
                }
            }

            return position;
        });

        const supportsDraggedComponent = computed(() => {
            if (pageBuilderStore.dragId === pageBuilderStore.highlightedId) {
                return false;
            }

            if (pageBuilderStore.draggedComponent && pageBuilderStore.highlightedComponent) {
                if (pageBuilderStore.highlightedComponent.siblingsShortcodes.length === 0) {
                    if (pageBuilderStore.draggedComponent.siblingsShortcodes.length === 0) {
                        return true;
                    } else {
                        return pageBuilderStore.draggedComponent.siblingsShortcodes.includes(
                            pageBuilderStore.highlightedComponent.name,
                        );
                    }
                }

                return pageBuilderStore.highlightedComponent.siblingsShortcodes.includes(
                    pageBuilderStore.draggedComponent.name,
                );
            }

            return false;
        });

        function supportsHighlightedPosition(position: DropPosition): boolean {
            return pageBuilderStore.highlightedComponent
                ? pageBuilderStore.highlightedComponent.siblingsPosition.includes(position)
                : false;
        }

        watch(position, () => (pageBuilderStore.dropPosition = position.value));

        watch(
            () => pageBuilderStore.$state,
            () => {
                const element = pageBuilderStore.highlightedComponentElement;

                if (!element) {
                    return;
                }

                const rect = element.getBoundingClientRect();

                lineRect.width = rect.right - rect.left;
                lineRect.height = rect.bottom - rect.top;
                lineRect.x = rect.left;
                lineRect.y = rect.top;
            },
            { deep: true },
        );

        return {
            styles,
            position,
            active: computed(() => mouseStore.over && !!position.value),
        };
    },
});
</script>
