<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div v-if="active" id="n9-page-builder-tool-select" :style="styles">
        <div v-if="!isBeingDragged" class="n9-wrapper">
            <div v-if="ancestors.length > 0" ref="ancestorsRef" class="n9-ancestors">
                <i class="fas fa-chevron-down n9-text-white"></i>
                <ul v-if="areAncestorsVisible" ref="ancestorsListRef">
                    <li v-for="(ancestor, i) in ancestors" :key="'ancestor-' + ancestor.id">
                        <button
                            :ref="
                                (el) => {
                                    ancestorsButtonRefs[i] = el;
                                }
                            "
                            type="button"
                        >
                            {{ capitalCase(ancestor.name) }}
                        </button>
                    </li>
                </ul>
            </div>
            <h3 ref="componentLabelRef">
                {{ label }}
            </h3>
            <button ref="optionsRef" class="n9-options n9-text-white">
                <i class="fas fa-cog"></i>
            </button>
        </div>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, onBeforeUnmount, onBeforeUpdate, onUpdated, Ref, ref } from 'vue';
import GenericObject from 'admin/interfaces/GenericObject';
import { usePageBuilderStore } from 'admin/vue/stores/pageBuilder';
import { capitalCase } from 'change-case';

export default defineComponent({
    name: 'PageBuilderToolSelect',
    setup() {
        const pageBuilderStore = usePageBuilderStore();
        const areAncestorsVisible = ref(false);
        const ancestorsRef: Ref<HTMLElement | null> = ref(null);
        const ancestorsListRef: Ref<HTMLElement | null> = ref(null);
        const ancestorsButtonRefs = ref([]);
        const componentLabelRef: Ref<HTMLElement | null> = ref(null);
        const optionsRef: Ref<HTMLElement | null> = ref(null);
        const ancestors = computed(() =>
            pageBuilderStore.getComponentAncestors(pageBuilderStore.selectedComponent).reverse(),
        );
        const ancestorsButtonsListeners: Map<number, Map<string, EventListenerOrEventListenerObject>> = new Map();

        onBeforeUpdate(() => {
            removeListeners();
            ancestorsButtonRefs.value = [];
        });

        onUpdated(() => {
            ancestorsRef.value?.addEventListener('mouseover', showAncestors);
            ancestorsRef.value?.addEventListener('mouseleave', hideAncestors);
            ancestorsListRef.value?.addEventListener('mouseleave', hideAncestors);
            componentLabelRef.value?.addEventListener('mousedown', drag);
            optionsRef.value?.addEventListener('click', toggleContextMenu);

            ancestorsButtonRefs.value.forEach((button: HTMLElement, i) => {
                if (!button) {
                    return;
                }

                const clickListener = (event: Event) => {
                    event.stopPropagation();
                    selectComponent(ancestors.value[i].id);
                };

                const mouseOverListener = (event: Event) => {
                    event.stopPropagation();
                    highlightComponent(ancestors.value[i].id);
                };

                const stopPropagation = (event: Event) => event.stopPropagation();

                const map: Map<string, EventListenerOrEventListenerObject> = new Map();
                map.set('click', clickListener);
                map.set('mouseover', mouseOverListener);
                map.set('mousemove', stopPropagation);
                ancestorsButtonsListeners.set(i, map);

                button.addEventListener('click', clickListener);
                button.addEventListener('mouseover', mouseOverListener);
                button.addEventListener('mousemove', stopPropagation);
            });
        });

        onBeforeUnmount(() => {
            removeListeners();
        });

        function removeListeners() {
            ancestorsRef.value?.removeEventListener('mouseover', showAncestors);
            ancestorsRef.value?.removeEventListener('mouseleave', hideAncestors);
            ancestorsListRef.value?.removeEventListener('mouseleave', hideAncestors);
            componentLabelRef.value?.removeEventListener('mousedown', drag);
            optionsRef.value?.removeEventListener('click', toggleContextMenu);

            ancestorsButtonsListeners.forEach((listeners, i) => {
                listeners.forEach((listener, eventType) => {
                    (ancestorsButtonRefs.value[i] as HTMLElement)?.removeEventListener(eventType, listener);
                });
            });

            ancestorsButtonsListeners.clear();
        }

        function showAncestors() {
            areAncestorsVisible.value = true;
        }

        function hideAncestors() {
            areAncestorsVisible.value = false;
        }

        const styles = computed(() => {
            const styles: GenericObject<string> = {};

            if (!pageBuilderStore.selectedId) {
                return styles;
            }

            const element = pageBuilderStore.document.querySelector(
                `[data-component-id='${pageBuilderStore.selectedId}']`,
            )!;

            const rect = element.getBoundingClientRect();
            const scrollLeft = pageBuilderStore.document.documentElement.scrollLeft;
            const scrollTop = pageBuilderStore.document.documentElement.scrollTop;

            styles.width = `${rect.right - rect.left}px`;
            styles.height = `${rect.bottom - rect.top}px`;
            styles.transform = `translateX(${rect.left + scrollLeft}px) translateY(${rect.top + scrollTop}px)`;

            return styles;
        });

        function selectComponent(componentId: string) {
            pageBuilderStore.selectedId = componentId;
            hideAncestors();
        }

        function highlightComponent(componentId: string) {
            pageBuilderStore.highlightedId = componentId;
        }

        function drag() {
            pageBuilderStore.dragId = pageBuilderStore.selectedId;
        }

        function toggleContextMenu() {
            pageBuilderStore.isContextMenuVisible = !pageBuilderStore.isContextMenuVisible;
        }

        return {
            ancestorsRef,
            ancestorsListRef,
            ancestorsButtonRefs,
            componentLabelRef,
            optionsRef,
            styles,
            active: computed(() => !!pageBuilderStore.selectedId),
            label: computed(() => pageBuilderStore.selectedComponentLabel),
            ancestors,
            isBeingDragged: computed(() => pageBuilderStore.dragId !== undefined),
            areAncestorsVisible,
            drag,
            toggleContextMenu: pageBuilderStore.toggleContextMenu,
            capitalCase,
        };
    },
});
</script>
