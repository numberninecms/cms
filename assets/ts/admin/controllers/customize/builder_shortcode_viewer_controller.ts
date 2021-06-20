/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from 'stimulus';
import { eventBus } from 'admin/admin';
import { EVENT_MODAL_SHOW, EVENT_PAGE_BUILDER_SHOW_SHORTCODE } from 'admin/events/events';
import PageBuilderShowShortcodeEvent from 'admin/events/PageBuilderShowShortcodeEvent';
import axios from 'axios';
import ModalShowEvent from 'admin/events/ModalShowEvent';

export default class extends Controller {
    public static targets = ['content'];
    public static values = {
        renderUrl: String,
    };

    private readonly contentTarget: HTMLElement;
    private readonly renderUrlValue: string;

    public connect(): void {
        eventBus.on<PageBuilderShowShortcodeEvent>(EVENT_PAGE_BUILDER_SHOW_SHORTCODE, (event) => {
            if (event?.component) {
                void axios
                    .post(this.renderUrlValue, {
                        component: event.component,
                        beautify: true,
                    })
                    .then((response) => {
                        this.contentTarget.innerHTML = response.data;
                        eventBus.emit<ModalShowEvent>(EVENT_MODAL_SHOW, { modalId: 'shortcode_viewer' });
                    });
            }
        });
    }
}
