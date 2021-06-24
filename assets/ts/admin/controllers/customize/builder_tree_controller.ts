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
import { EVENT_PAGE_BUILDER_REQUEST_FOR_DELETE_COMPONENT, EVENT_PAGE_BUILDER_COMPONENT_DELETED } from 'admin/events/events';
import PageBuilderRequestForDeleteComponentEvent from 'admin/events/PageBuilderRequestForDeleteComponentEvent';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import usePageBuilderHelpers from 'admin/vue/functions/pageBuilderHelpers';
import PageBuilderComponentDeletedEvent from 'admin/events/PageBuilderComponentDeletedEvent';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    public connect(): void {
        eventBus.on<PageBuilderRequestForDeleteComponentEvent>(
            EVENT_PAGE_BUILDER_REQUEST_FOR_DELETE_COMPONENT,
            this.deleteComponent.bind(this),
        );
    }

    private deleteComponent(event?: PageBuilderRequestForDeleteComponentEvent): void {
        if (!event) {
            return;
        }

        void Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: 'var(--color-primary)',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                const { findComponentInTree, removeComponentInTree } = usePageBuilderHelpers();

                if (findComponentInTree(event.componentToDelete.id, event.tree)) {
                    removeComponentInTree(event.tree, event.componentToDelete.id);

                    eventBus.emit<PageBuilderComponentDeletedEvent>(EVENT_PAGE_BUILDER_COMPONENT_DELETED, {
                        tree: event.tree,
                        deletedComponent: event.componentToDelete,
                    });
                }
            }
        });
    }
}
