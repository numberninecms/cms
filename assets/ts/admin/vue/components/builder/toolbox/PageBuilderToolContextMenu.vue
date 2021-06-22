<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div v-show="isContextMenuVisible" id="n9-page-builder-tool-context-menu" :style="styles()">
        <div class="n9-menu-buttons-list">
            <button ref="duplicateRef"><i class="far fa-clone"></i> Duplicate</button>
            <button ref="savePresetRef"><i class="far fa-save"></i> Save as preset</button>
            <button ref="showShortcodeRef"><i class="far fa-eye"></i> Show shortcode</button>
            <button ref="deleteRef" type="button"><i class="far fa-trash-alt"></i> Delete</button>
        </div>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, onBeforeUnmount, onMounted, ref, Ref, watch } from 'vue';
import { usePageBuilderStore } from 'admin/vue/stores/pageBuilder';
import GenericObject from 'admin/interfaces/GenericObject';
import { eventBus } from 'admin/admin';
import PageBuilderSavePresetEvent from 'admin/events/PageBuilderSavePresetEvent';
import {
    EVENT_PAGE_BUILDER_DELETE_COMPONENT,
    EVENT_PAGE_BUILDER_SAVE_PRESET,
    EVENT_PAGE_BUILDER_SHOW_SHORTCODE,
} from 'admin/events/events';
import PageBuilderShowShortcodeEvent from 'admin/events/PageBuilderShowShortcodeEvent';
import PageBuilderDeleteComponentEvent from 'admin/events/PageBuilderDeleteComponentEvent';

export default defineComponent({
    name: 'PageBuilderToolContextMenu',
    setup() {
        const pageBuilderStore = usePageBuilderStore();
        const duplicateRef: Ref<HTMLDivElement | null> = ref(null);
        const savePresetRef: Ref<HTMLDivElement | null> = ref(null);
        const showShortcodeRef: Ref<HTMLDivElement | null> = ref(null);
        const deleteRef: Ref<HTMLDivElement | null> = ref(null);

        onMounted(() => {
            duplicateRef.value?.addEventListener('click', duplicateComponent);
            savePresetRef.value?.addEventListener('click', savePreset);
            showShortcodeRef.value?.addEventListener('click', showShortcode);
            deleteRef.value?.addEventListener('click', deleteComponent);
        });

        onBeforeUnmount(() => {
            duplicateRef.value?.removeEventListener('click', duplicateComponent);
            savePresetRef.value?.removeEventListener('click', savePreset);
            showShortcodeRef.value?.removeEventListener('click', showShortcode);
            deleteRef.value?.removeEventListener('click', deleteComponent);
        });

        function duplicateComponent(): void {
            hideContextMenu();
            pageBuilderStore.duplicateComponent(pageBuilderStore.selectedId!);
        }

        function savePreset(): void {
            hideContextMenu();

            if (!pageBuilderStore.selectedComponent) {
                return;
            }

            eventBus.emit<PageBuilderSavePresetEvent>(EVENT_PAGE_BUILDER_SAVE_PRESET, {
                component: pageBuilderStore.selectedComponent,
            });
        }

        function showShortcode(): void {
            hideContextMenu();

            if (!pageBuilderStore.selectedComponent) {
                return;
            }

            eventBus.emit<PageBuilderShowShortcodeEvent>(EVENT_PAGE_BUILDER_SHOW_SHORTCODE, {
                component: pageBuilderStore.selectedComponent,
            });
        }

        function deleteComponent(): void {
            hideContextMenu();

            if (!pageBuilderStore.selectedComponent) {
                return;
            }

            eventBus.emit<PageBuilderDeleteComponentEvent>(EVENT_PAGE_BUILDER_DELETE_COMPONENT, {
                tree: pageBuilderStore.pageComponents,
                componentToDelete: pageBuilderStore.selectedComponent,
            });
        }

        function hideContextMenu(): void {
            pageBuilderStore.isContextMenuVisible = false;
        }

        function styles(): GenericObject<string> {
            const styles: GenericObject<string> = {};

            const element = pageBuilderStore.document.querySelector('#n9-page-builder-tool-select .n9-options');

            if (element) {
                const rect = element.getBoundingClientRect();
                const scrollLeft = pageBuilderStore.document.documentElement.scrollLeft;
                const scrollTop = pageBuilderStore.document.documentElement.scrollTop;

                styles.transform = `translateX(${rect.left + scrollLeft}px) translateY(${rect.top + scrollTop + 20}px)`;
            }

            return styles;
        }

        watch(
            () => pageBuilderStore.selectedId,
            () => {
                hideContextMenu();
            },
        );

        return {
            duplicateRef,
            savePresetRef,
            showShortcodeRef,
            deleteRef,
            styles,
            isContextMenuVisible: computed(() => pageBuilderStore.isContextMenuVisible),
        };
    },
});
</script>
