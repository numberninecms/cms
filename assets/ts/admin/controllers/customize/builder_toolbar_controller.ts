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
import { EVENT_PAGE_BUILDER_CHANGE_VIEWPORT_SIZE_EVENT } from 'admin/events/events';
import { PageBuilderChangeViewportSizeEvent } from 'admin/events/PageBuilderChangeViewportSizeEvent';

export default class extends Controller {
    public static targets = ['save', 'tree', 'phone', 'tablet', 'desktop'];

    private readonly saveTarget: HTMLButtonElement;
    private readonly treeTarget: HTMLButtonElement;
    private readonly phoneTarget: HTMLButtonElement;
    private readonly tabletTarget: HTMLButtonElement;
    private readonly desktopTarget: HTMLButtonElement;

    public connect(): void {
        this.desktopTarget.addEventListener('click', this.setDesktopSize.bind(this));
        this.tabletTarget.addEventListener('click', this.setTabletSize.bind(this));
        this.phoneTarget.addEventListener('click', this.setPhoneSize.bind(this));
    }

    private setDesktopSize(): void {
        this.changeViewportSize('lg');
    }

    private setTabletSize(): void {
        this.changeViewportSize('md');
    }

    private setPhoneSize(): void {
        this.changeViewportSize('xs');
    }

    private changeViewportSize(size: ViewportSize) {
        eventBus.emit<PageBuilderChangeViewportSizeEvent>(EVENT_PAGE_BUILDER_CHANGE_VIEWPORT_SIZE_EVENT, size);
    }
}
