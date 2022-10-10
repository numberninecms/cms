/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    public add(event: MouseEvent): void {
        const list = document.querySelector(
            (event.currentTarget as HTMLElement).dataset.listSelector as string,
        ) as HTMLElement;
        let counter = parseInt(list.dataset.widgetCounter as string) || list.children.length;
        let newWidget = list.dataset.prototype;

        newWidget = newWidget!.replace(/__name__/g, counter.toString());
        list.dataset.widgetCounter = (++counter).toString();

        const newElem = document.createElement('div');
        newElem.innerHTML = newWidget;
        list.append(newElem.firstChild as Node);
    }

    public delete(event: MouseEvent): void {
        const item = (event.currentTarget as HTMLElement).closest('.collection-item');

        if (item) {
            item.parentNode?.removeChild(item);
        }
    }
}
