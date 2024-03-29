/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { defineStore } from 'pinia';
import axios from 'axios';
import { v4 as uuidv4 } from 'uuid';
import PageComponent from 'admin/interfaces/PageComponent';
import ComponentsApiResponse from 'admin/interfaces/ComponentsApiResponse';
import PageBuilderApp from 'admin/classes/PageBuilderApp';
import { Direction } from 'admin/types/Direction';
import usePageBuilderHelpers from 'admin/vue/functions/pageBuilderHelpers';
import { capitalCase } from 'change-case';
import { DropPosition } from 'admin/types/DropPosition';
import { ViewportSize } from 'admin/types/ViewportSize';
import GenericObject from 'admin/interfaces/GenericObject';
import Form from 'admin/interfaces/Form';
import { eventBus } from 'admin/admin';
import { EVENT_PAGE_BUILDER_COMPONENT_UPDATED } from 'admin/events/events';

export const usePageBuilderStore = defineStore({
    id: 'pageBuilder',
    state() {
        return {
            app: undefined as PageBuilderApp | undefined,
            componentsApiUrl: '',
            pageComponents: [] as PageComponent[],
            availablePageComponents: {} as GenericObject<PageComponent>,
            pageComponentForms: {} as GenericObject<Form>,
            highlightedId: undefined as string | undefined,
            selectedId: undefined as string | undefined,
            dragId: undefined as string | undefined,
            isContextMenuVisible: false,
            dropPosition: undefined as DropPosition | undefined,
            viewportSize: 'lg' as ViewportSize,
        };
    },
    getters: {
        document: (): Document =>
            (document.getElementById('page-builder-content-frame')!.querySelector('iframe') as HTMLIFrameElement)
                .contentWindow!.document,

        highlightedComponentElement(): Element | undefined {
            if (!this.highlightedId) {
                return undefined;
            }

            return this.document.querySelector(`[data-component-id='${this.highlightedId}']`)!;
        },

        highlightedComponentLabel(): string {
            return this.getComponentLabel(this.highlightedId);
        },

        highlightedComponent(): PageComponent | undefined {
            return this.highlightedId ? this.getComponentById(this.highlightedId) : undefined;
        },

        selectedComponentLabel(): string {
            return this.getComponentLabel(this.selectedId);
        },

        selectedComponent(): PageComponent | undefined {
            return this.selectedId ? this.getComponentById(this.selectedId) : undefined;
        },

        draggedComponent(): PageComponent | undefined {
            return this.dragId ? this.getComponentById(this.dragId) : undefined;
        },

        getComponentLabel() {
            return (componentId: string | undefined): string => {
                if (!componentId) {
                    return '';
                }

                const component = this.getComponentById(componentId);

                return component ? capitalCase(component.label) : '';
            };
        },

        getComponentById() {
            return (id: string, components?: PageComponent[]): PageComponent | undefined => {
                if (!components) {
                    components = this.pageComponents;
                }

                const { findComponentInTree } = usePageBuilderHelpers();

                return findComponentInTree(id, components);
            };
        },

        getComponentAncestors() {
            return (component: PageComponent | undefined): PageComponent[] => {
                if (!component) {
                    return [];
                }

                const ancestors: PageComponent[] = [];
                const parent = this.getComponentParent(component);

                if (parent) {
                    ancestors.push(...this.getComponentAncestors(parent), parent);
                }

                return ancestors;
            };
        },

        getComponentParent() {
            return (component: PageComponent | undefined): PageComponent | undefined => {
                if (!component || !component.parentId) {
                    return undefined;
                }

                return this.getComponentById(component.parentId);
            };
        },

        defaultTextComponent(): PageComponent {
            return {
                id: uuidv4(),
                name: 'text',
                parameters: { content: 'Add a new component to this page...' },
                computed: [],
                position: 0,
                label: 'Text',
                children: [],
                parentId: undefined,
                siblingsPosition: ['top' as Direction, 'bottom' as Direction],
                siblingsShortcodes: [],
                icon: 'file-alt',
                editable: true,
                container: false,
                responsive: [],
            };
        },
    },
    actions: {
        setup({ app, componentsApiUrl }: { app: PageBuilderApp; componentsApiUrl: string }) {
            this.app = app;
            this.componentsApiUrl = componentsApiUrl;
        },

        async fetchComponents(): Promise<void> {
            const { prepareTree } = usePageBuilderHelpers();
            const response = await axios.get(this.componentsApiUrl);
            const { tree, templates, components, controls }: ComponentsApiResponse = response.data;

            if (tree.length === 0) {
                tree.push(this.defaultTextComponent);
            }

            prepareTree(tree);

            this.pageComponents = tree;
            this.pageComponentForms = controls;
            this.availablePageComponents = components;

            for (const template of Object.keys(templates)) {
                this.app!.compileComponent(template, templates[template]);
            }
        },

        async saveComponents(): Promise<void> {
            await axios.post(this.componentsApiUrl, {
                components: this.pageComponents,
            });
        },

        duplicateComponent(id: string): void {
            const { duplicateComponentInTree } = usePageBuilderHelpers();
            const component = this.getComponentById(id);

            if (component) {
                duplicateComponentInTree(this.pageComponents, component);
            }
        },

        deleteComponent(id: string): void {
            const { removeComponentInTree } = usePageBuilderHelpers();

            if (id === this.selectedId) {
                this.selectedId = undefined;
            }

            if (this.getComponentById(id)) {
                this.pageComponents = removeComponentInTree(this.pageComponents, id);
            }
        },

        toggleContextMenu() {
            this.isContextMenuVisible = !this.isContextMenuVisible;
        },

        updateComponentComputedParameter({
            id,
            parameter,
            value,
        }: {
            id: string;
            parameter: string;
            value: any;
        }): void {
            const component = this.getComponentById(id);

            if (component) {
                component.computed[parameter] = value;
                eventBus.emit(EVENT_PAGE_BUILDER_COMPONENT_UPDATED, { component });
            }
        },
    },
});
