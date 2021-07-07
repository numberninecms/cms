/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import Preloader from 'admin/interfaces/Preloader';
import PageComponent from 'admin/interfaces/PageComponent';

export class MenuPreloader implements Preloader {
    private component: PageComponent;

    public constructor(component: PageComponent) {
        this.component = component;
    }

    public preload(): void {
        if (this.component.parameters.id) {
            // await this.$store.dispatch('Menu/queryMenus');
            // await this.$store.dispatch('PageBuilder/updateComponentComputedParameter', {
            //     id: this.component.id,
            //     parameter: 'selectedMenuName',
            //     value: this.$store.state.Menu.menus.find((menu) => menu.id === this.component.parameters.id)?.name,
            // });
            // EventBus.emit('PageBuilder:update-parameter-after');
        }
    }
}
