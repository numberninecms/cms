/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import * as changeCase from 'change-case';
import { App, compile, createApp, h } from 'vue';
import PageBuilder from 'admin/vue/components/builder/PageBuilder.vue';
import { createPinia } from 'pinia';
import { usePageBuilderStore } from 'admin/vue/stores/pageBuilder';
import PageBuilderComponent from 'admin/vue/components/builder/PageBuilderComponent.vue';

export default class PageBuilderApp {
    private app: App;

    public constructor(element: Element, componentsApiUrl: string) {
        this.createApp(element);
        this.registerBuiltInComponents();
        this.setupStoreAndFetchData(componentsApiUrl);
    }

    private createApp(element: Element): void {
        const el = document.createElement('div');
        element.parentNode!.insertBefore(el, element);

        const renderFunction = compile(element.outerHTML);
        this.app = createApp({
            components: { PageBuilder },
            render: renderFunction,
        });

        this.app.use(createPinia());
        this.app.mount(el);

        element.parentNode!.removeChild(element);
    }

    private registerBuiltInComponents(): void {
        this.app.component('PageBuilderStyle', {
            render() {
                return h('style', {}, this.$slots.default()); // eslint-disable-line
            },
        });
    }

    private setupStoreAndFetchData(componentsApiUrl: string): void {
        const store = usePageBuilderStore();
        store.setup({ app: this, componentsApiUrl });
        void store.fetchComponents();
    }

    public compileComponent(componentName: string, template: string): void {
        const name = changeCase.pascalCase(componentName) + 'PageBuilderComponent';

        this.app.component(name, {
            components: { PageBuilderComponent, PageBuilderStyle: this.app.component('PageBuilderStyle')! },
            props: ['parameters', 'responsive', 'computed', 'children', 'selfInstance', 'viewSize'],
            setup(props) {
                function isResponsive(field: string): boolean {
                    return props.responsive.includes(field); // eslint-disable-line
                }

                function getResponsiveValue(field: string): any {
                    if (!isResponsive(field)) {
                        return props.parameters[field]; // eslint-disable-line
                    }

                    return Object.hasOwnProperty.call(props.parameters[field], props.viewSize) // eslint-disable-line
                        ? props.parameters[field][props.viewSize] // eslint-disable-line
                        : '';
                }

                return {
                    isResponsive,
                    getResponsiveValue,
                };
            },
            template,
        });
    }
}
