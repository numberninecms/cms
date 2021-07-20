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

export default class extends Controller {
    private deleteEntity(event: MouseEvent): void {
        event.preventDefault();

        const tr = (event.target as HTMLElement).closest('tr') as HTMLElement;
        const entityId = tr.dataset.entityId!;

        void Swal.fire({
            title: 'Are you sure to delete?',
            icon: 'warning',
            heightAuto: false,
            showCancelButton: true,
            confirmButtonColor: 'var(--color-primary)',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                const checkbox = tr.querySelector(
                    `input[name="admin_content_entity_index_form[entity_${entityId}]"]`,
                ) as HTMLInputElement;
                checkbox.checked = true;

                const form = tr.closest('form')!;
                const deleteButton = Array.from(form.querySelectorAll('button[type="submit"]')).find(
                    (b) => (b as HTMLButtonElement).value === 'delete',
                ) as HTMLButtonElement;

                deleteButton.click();
            }
        });
    }

    private restoreEntity(event: MouseEvent): void {
        event.preventDefault();

        const tr = (event.target as HTMLElement).closest('tr') as HTMLElement;
        const entityId = tr.dataset.entityId!;

        void Swal.fire({
            title: 'Are you sure to restore?',
            icon: 'question',
            heightAuto: false,
            showCancelButton: true,
            confirmButtonColor: 'var(--color-primary)',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, restore it!',
        }).then((result) => {
            if (result.isConfirmed) {
                const checkbox = tr.querySelector(
                    `input[name="admin_content_entity_index_form[entity_${entityId}]"]`,
                ) as HTMLInputElement;
                checkbox.checked = true;

                const form = tr.closest('form')!;
                const restoreButton = Array.from(form.querySelectorAll('button[type="submit"]')).find(
                    (b) => (b as HTMLButtonElement).value === 'restore',
                ) as HTMLButtonElement;

                restoreButton.click();
            }
        });
    }
}
