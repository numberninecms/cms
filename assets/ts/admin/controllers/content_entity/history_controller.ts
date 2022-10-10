/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from '@hotwired/stimulus';
import { createApp } from 'vue';
import ContentEntityHistory from 'admin/vue/components/content_entity/ContentEntityHistory.vue';
import VueDiff from 'vue-diff';
import 'vue-diff/dist/index.css';
import { eventBus } from 'admin/admin';
import { EVENT_CONTENT_ENTITY_REVERT_TO_REVISION } from 'admin/events/events';
import ContentEntityRevertToRevisionEvent from 'admin/events/ContentEntityRevertToRevisionEvent';
import axios from 'axios';
import Routing from 'assets/ts/routing';
import Swal from 'sweetalert2';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    public static values = {
        id: Number,
        contentType: String,
    };

    private readonly idValue: number;
    private readonly contentTypeValue: string;

    public connect(): void {
        createApp(ContentEntityHistory, {
            entityId: this.idValue,
            contentType: this.contentTypeValue,
        })
            .use(VueDiff)
            .mount(this.element);

        eventBus.on(EVENT_CONTENT_ENTITY_REVERT_TO_REVISION, this.revertToVersion.bind(this));
    }

    private revertToVersion(event: ContentEntityRevertToRevisionEvent): void {
        void Swal.fire({
            title: 'Are you sure?',
            text: `All revisions past ${event.date} will be permanently deleted.`,
            icon: 'warning',
            heightAuto: false,
            showCancelButton: true,
            confirmButtonColor: 'var(--color-primary)',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, revert to this version!',
        }).then(async (result) => {
            if (result.isConfirmed) {
                await axios.post(
                    Routing.generate('numbernine_admin_contententity_revert_item', {
                        type: event.contentType,
                        id: event.entityId,
                        version: event.version,
                    }),
                );

                window.location.reload();
            }
        });
    }
}
