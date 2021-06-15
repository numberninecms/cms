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
import PageComponent from 'admin/interfaces/PageComponent';
import ComponentsApiResponse from 'admin/interfaces/ComponentsApiResponse';
import PageBuilderApp from 'admin/classes/PageBuilderApp';
import useStringHelpers from 'admin/vue/functions/stringHelpers';

export const usePageBuilderStore = defineStore({
    id: 'pageBuilder',
    state() {
        return {
            app: undefined as PageBuilderApp | undefined,
            componentsApiUrl: '',
            pageComponents: [] as PageComponent[],
            availablePageComponents: [] as unknown as { [componentName: string]: PageComponent },
            highlightedId: undefined as string | undefined,
        };
    },
    getters: {
        document: (): Document =>
            (document.getElementById('page-builder-content-frame')!.querySelector('iframe') as HTMLIFrameElement)
                .contentWindow!.document,

        highlightedComponentLabel(): string {
            if (!this.highlightedId) {
                return '';
            }

            const { upperFirst } = useStringHelpers();
            const component = this.getComponentById(this.highlightedId);

            return component ? upperFirst(component.label) : '';
        },

        getComponentById() {
            return (id: string, components?: PageComponent[]): PageComponent | undefined => {
                if (!components) {
                    components = this.pageComponents;
                }

                for (const component of components) {
                    if (component.id === id) {
                        return component;
                    }

                    if (Object.hasOwnProperty.call(component, 'children') && component.children.length > 0) {
                        const found = this.getComponentById(id, component.children);

                        if (found) {
                            return found;
                        }
                    }
                }

                return undefined;
            };
        },
    },
    actions: {
        setup({ app, componentsApiUrl }: { app: PageBuilderApp; componentsApiUrl: string }) {
            this.app = app;
            this.componentsApiUrl = componentsApiUrl;
        },

        async fetchComponents(): Promise<void> {
            const response = await axios.get(this.componentsApiUrl);
            const { tree, templates, components }: ComponentsApiResponse = response.data;

            this.pageComponents = tree;
            this.availablePageComponents = components;

            for (const template in templates) {
                this.app!.compileComponent(template, templates[template]);
            }
        },
    },
});
