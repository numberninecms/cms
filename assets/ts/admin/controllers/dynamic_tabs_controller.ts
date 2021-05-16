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
    public static targets = ['tab', 'panel'];

    private readonly tabTargets: HTMLElement[];
    private readonly panelTargets: HTMLElement[];

    public connect(): void {
        this.panelTargets.forEach((panel) => {
            panel.classList.remove('hidden');
            panel.style.display = 'none';
        });
    }

    public activate(event: MouseEvent): void {
        event.preventDefault();
        this.tabTargets.forEach((tab) => {
            tab.classList.remove('active', 'font-semibold');
        });
        (event.currentTarget as HTMLElement).classList.add('active', 'font-semibold');
    }

    public showTab(event: MouseEvent): void {
        event.preventDefault();
        const tabId = (event.currentTarget as HTMLElement).dataset.tabId;

        const panel = this.panelTargets.find((panel) => panel.dataset.tabId === tabId);

        if (panel) {
            this.panelTargets.forEach((panel) => {
                panel.style.display = 'none';
            });

            panel.style.display = 'block';
        }
    }
}
