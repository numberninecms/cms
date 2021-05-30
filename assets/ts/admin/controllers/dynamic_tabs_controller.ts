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
        this.tabTargets.forEach((tab) => {
            tab.onclick = () => {
                window.history.replaceState(null, document.title, `#${tab.dataset.tabId as string}`);
            };
        });

        const currentTab = window.location.hash.substr(1);
        this.activateTab(currentTab ? currentTab : this.tabTargets[0].dataset.tabId);
    }

    public activate(event: MouseEvent): void {
        event.preventDefault();
        this.activateTab((event.currentTarget as HTMLElement).dataset.tabId);
    }

    private activateTab(tabId: string | undefined): void {
        this.tabTargets.forEach((tab) => {
            tab.classList.remove('active', 'font-semibold');
        });

        this.panelTargets.forEach((panel) => {
            panel.style.display = 'none';
        });

        if (tabId) {
            const tab = this.tabTargets.find((tab) => tab.dataset.tabId === tabId);

            if (tab) {
                tab.classList.add('active', 'font-semibold');
                this.panelTargets.find((panel) => panel.dataset.tabId === tabId)!.style.display = 'inherit';
            } else if (this.panelTargets.length > 0) {
                this.panelTargets[0].style.display = 'inherit';
                window.history.replaceState(null, document.title, window.location.pathname + window.location.search);
            }
        }
    }
}
