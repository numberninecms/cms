/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from 'stimulus';
import Swal from 'sweetalert2';
import axios from 'axios';
import Routing from 'assets/ts/routing';

export default class extends Controller {
    public static values = {
        id: Number,
        type: String,
    };

    private readonly idValue: number;
    private readonly typeValue: number;

    private deleteEntity(event: MouseEvent): void {
        event.preventDefault();

        void Swal.fire({
            title: 'Are you sure to delete?',
            icon: 'warning',
            heightAuto: false,
            showCancelButton: true,
            confirmButtonColor: 'var(--color-primary)',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            showLoaderOnConfirm: true,
            preConfirm: async () => {
                await axios.delete(
                    Routing.generate('numbernine_admin_contententity_delete_item', {
                        type: this.typeValue,
                        id: this.idValue,
                    }),
                );

                window.location.href = Routing.generate('numbernine_admin_content_entity_index', {
                    type: this.typeValue,
                });
            },
        });
    }
}
