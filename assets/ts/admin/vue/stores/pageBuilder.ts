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

export const usePageBuilderStore = defineStore({
    id: 'pageBuilder',
    state() {
        return {
            app: undefined as PageBuilderApp | undefined,
            componentsApiUrl: '',
            pageComponents: [] as PageComponent[],
            availablePageComponents: [] as unknown as { [componentName: string]: PageComponent },
        };
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
