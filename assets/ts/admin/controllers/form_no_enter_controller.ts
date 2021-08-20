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
        (this.element as HTMLFormElement).onkeydown = (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                return false;
            }
        };
    }
}
