/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import PageComponent from 'admin/interfaces/PageComponent';
import Preloader from 'admin/interfaces/Preloader';
import { usePageBuilderStore } from 'admin/vue/stores/pageBuilder';
import Menu from 'admin/interfaces/Menu';
import { useMenuStore } from 'admin/vue/stores/menu';

export class MenuPreloader implements Preloader {
    private readonly component: PageComponent;

    public constructor(component: PageComponent) {
        this.component = component;
    }

    public async preload(): Promise<void> {
        const menuStore = useMenuStore();
        const pageBuilderStore = usePageBuilderStore();

        let value: Menu | undefined;
        const id = this.component.parameters.id ? parseInt(this.component.parameters.id as string) : undefined;

        if (id) {
            await menuStore.fetchMenus();
            value = menuStore.menus.find((m) => m.id === id);
        }

        if (value) {
            pageBuilderStore.updateComponentComputedParameter({
                id: this.component.id,
                parameter: 'id',
                value,
            });
        }
    }
}
