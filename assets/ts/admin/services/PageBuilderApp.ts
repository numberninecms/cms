/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// import * as changeCase from 'change-case';
import { App, compile, createApp, h } from 'vue';
import PageBuilder from 'admin/vue/components/builder/PageBuilder.vue';

export default class PageBuilderApp {
    private app: App;

    public constructor(element: Element) {
        this.createApp(element);

        this.app.component('BuilderComponentStyle', {
            render() {
                return h('style', {}, this.$slots.default()); // eslint-disable-line
            },
        });
    }

    private createApp(element: Element): void {
        const el = document.createElement('div');
        element.parentNode!.insertBefore(el, element);

        const renderFunction = compile(element.outerHTML);
        this.app = createApp({
            components: { PageBuilder },
            render: renderFunction,
        });

        this.app.mount(el);

        element.parentNode!.removeChild(element);
    }
    //
    // public compileComponent(componentName: string, template: string) {
    //     const name = changeCase.pascalCase(componentName) + 'PageBuilderComponent';
    //     const componentExists = Object.keys((Vue as any).options.components).includes(name);
    //
    //     if (!componentExists) {
    //         Vue.component(name, {
    //             components: {PageBuilderComponent, PageBuilderStyle: Vue.component('PageBuilderStyle')},
    //             template,
    //             props: ['parameters', 'responsive', 'computed', 'children', 'selfInstance', 'viewSize'],
    //             methods: {
    //                 isResponsive(field) {
    //                     return this.responsive.includes(field);
    //                 },
    //                 getResponsiveValue(field) {
    //                     if (!this.isResponsive(field)) {
    //                         return this.parameters[field];
    //                     }
    //
    //                     return this.parameters[field].hasOwnProperty(this.viewSize) ? this.parameters[field][this.viewSize] : '';
    //                 }
    //             }
    //         });
    //     }
    // }
}
