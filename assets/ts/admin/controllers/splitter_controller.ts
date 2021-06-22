/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from 'stimulus';
import Split from 'split.js';

export default class extends Controller {
    public static targets = ['panel'];

    private readonly panelTargets: HTMLElement[];

    public connect(): void {
        Split(this.panelTargets, {
            sizes: [80, 20],
            minSize: this.panelTargets.map((panel) => (panel.dataset.minSize ? parseInt(panel.dataset.minSize) : 0)),
            expandToMin: true,
        });
    }
}
