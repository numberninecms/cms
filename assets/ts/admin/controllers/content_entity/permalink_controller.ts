/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from 'stimulus';
import { paramCase } from 'change-case';

export default class extends Controller {
    public static targets = ['link', 'editable', 'title', 'input', 'target'];

    private readonly linkTarget: HTMLElement;
    private readonly editableTarget: HTMLElement;
    private readonly titleTarget: HTMLInputElement;
    private readonly inputTarget: HTMLInputElement;
    private readonly targetTarget: HTMLInputElement;

    public connect(): void {
        this.inputTarget.onkeyup = (event) => {
            event.stopPropagation();

            if (event.key === 'Enter') {
                this.save();
            }

            if (event.key === 'Escape') {
                this.cancel();
            }
        };
    }

    public edit(): void {
        this.inputTarget.value = this.linkTarget.querySelector('.slug')!.innerHTML;

        this.linkTarget.style.display = 'none';
        this.editableTarget.style.display = 'flex';

        this.inputTarget.focus();
    }

    public save(): void {
        this.linkTarget.style.display = 'flex';
        this.editableTarget.style.display = 'none';

        const newSlug = this.inputTarget.value ? paramCase(this.inputTarget.value) : paramCase(this.titleTarget.value);

        this.targetTarget.value = newSlug;
        this.linkTarget.querySelector('.slug')!.innerHTML = newSlug;
    }

    public cancel(): void {
        this.linkTarget.style.display = 'flex';
        this.editableTarget.style.display = 'none';
    }
}
