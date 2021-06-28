/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from 'stimulus';
import { ViewportSize } from 'admin/types/ViewportSize';
import { eventBus } from 'admin/admin';
import {
    EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_VIEWPORT_SIZE_EVENT,
    EVENT_PAGE_BUILDER_REQUEST_FOR_SHOW_COMPONENTS_TREE,
} from 'admin/events/events';

export default class extends Controller {
    public setDesktopSize(): void {
        this.changeViewportSize('lg');
    }

    public setTabletSize(): void {
        this.changeViewportSize('md');
    }

    public setPhoneSize(): void {
        this.changeViewportSize('xs');
    }

    public showTree(): void {
        eventBus.emit(EVENT_PAGE_BUILDER_REQUEST_FOR_SHOW_COMPONENTS_TREE);
    }

    private changeViewportSize(size: ViewportSize) {
        eventBus.emit(EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_VIEWPORT_SIZE_EVENT, size);
    }
}
