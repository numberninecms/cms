/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from '@hotwired/stimulus';
import slugify from 'slugify';

export default class extends Controller {
    public static targets = ['main', 'slug'];

    private readonly mainTarget: HTMLInputElement;
    private readonly slugTarget: HTMLInputElement;

    public connect(): void {
        if (this.mainTarget && this.slugTarget) {
            this.mainTarget.addEventListener('keyup', (event) => {
                this.slugTarget.value = slugify((event.currentTarget as HTMLInputElement).value, { lower: true });
            });
        }
    }
}
