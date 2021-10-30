/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import Swal, { SweetAlertIcon } from 'sweetalert2';

interface Options {
    event: MouseEvent;
    formName: string;
    formCheckboxPrefix: string;
    button: string;
    confirm?:
        | true
        | {
              icon?: SweetAlertIcon;
              title?: string;
              verb?: string;
          };
}

function confirm(options: Options): void {
    void Swal.fire({
        title:
            options.confirm === true
                ? `Are you sure to ${options.button}?`
                : options.confirm!.title ?? `Are you sure to ${options.confirm!.verb ?? options.button}?`,
        icon: options.confirm === true || !options.confirm!.icon ? 'question' : options.confirm!.icon,
        heightAuto: false,
        showCancelButton: true,
        confirmButtonText: `Yes, ${
            options.confirm === true || !options.confirm!.verb ? options.button : options.confirm!.verb
        } it!`,
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-color-primary mr-3',
            cancelButton: 'btn btn-color-white border border-secondary',
        },
    }).then((result) => {
        if (result.isConfirmed) {
            submitForm(options);
        }
    });
}

function submitForm(options: Options): void {
    const tr = (options.event.target as HTMLElement).closest('tr') as HTMLElement;
    const id = tr.dataset.entityId!;

    const checkbox = tr.querySelector(
        `input[name="${options.formName}[${options.formCheckboxPrefix}_${id}]"]`,
    ) as HTMLInputElement;
    checkbox.checked = true;

    const form = tr.closest('form')!;
    const button = Array.from(form.querySelectorAll('button[type="submit"]')).find(
        (b) => (b as HTMLButtonElement).value === options.button,
    ) as HTMLButtonElement;

    button.click();
}

export default function checkAndSubmit(options: Options): void {
    if (options.confirm !== undefined) {
        confirm(options);
    } else {
        submitForm(options);
    }
}
