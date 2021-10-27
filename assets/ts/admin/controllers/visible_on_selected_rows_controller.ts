/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from 'stimulus';

export default class extends Controller {
    public connect(): void {
        const form = (this.element as HTMLElement).closest('form');
        const rows = form?.querySelectorAll('.select-row input');

        rows?.forEach((checkbox) => {
            checkbox.addEventListener('input', () => {
                if (Array.from(rows).filter((c) => (c as HTMLInputElement).checked).length > 0) {
                    (this.element as HTMLElement).classList.remove('hidden');
                    (this.element as HTMLElement).classList.add('flex');
                } else {
                    (this.element as HTMLElement).classList.remove('flex');
                    (this.element as HTMLElement).classList.add('hidden');
                }
            });
        });
    }
}
