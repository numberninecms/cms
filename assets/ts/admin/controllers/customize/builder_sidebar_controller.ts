/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from '@hotwired/stimulus';
import { createApp } from 'vue';
import PageBuilderComponentsTree from 'admin/vue/components/builder/PageBuilderComponentsTree.vue';
import { eventBus } from 'admin/admin';
import PageBuilderRequestForAddToContentEvent from 'admin/events/PageBuilderRequestForAddToContentEvent';
import {
    EVENT_PAGE_BUILDER_COMPONENTS_LOADED,
    EVENT_PAGE_BUILDER_REQUEST_FOR_ADD_TO_CONTENT,
    EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_COMPONENTS_TREE,
    EVENT_PAGE_BUILDER_REQUEST_FOR_EDIT_COMPONENT,
    EVENT_PAGE_BUILDER_REQUEST_FOR_SHOW_COMPONENTS_TREE,
} from 'admin/events/events';
import GenericObject from 'admin/interfaces/GenericObject';
import PageComponent from 'admin/interfaces/PageComponent';
import usePageBuilderHelpers from 'admin/vue/functions/pageBuilderHelpers';
import { v4 as uuidv4 } from 'uuid';

export default class extends Controller {
    public static targets = ['panel', 'tree', 'componentsList'];

    private readonly panelTargets: HTMLElement[];

    private availableComponents?: GenericObject<PageComponent>;
    private event?: PageBuilderRequestForAddToContentEvent;

    public connect(): void {
        this.showTree();
        createApp(PageBuilderComponentsTree).mount(this.getPanel('tree')!);

        eventBus.on(EVENT_PAGE_BUILDER_COMPONENTS_LOADED, (event) => {
            this.availableComponents = event.availableComponents;
        });

        eventBus.on(EVENT_PAGE_BUILDER_REQUEST_FOR_ADD_TO_CONTENT, this.addToContent.bind(this));
        eventBus.on(EVENT_PAGE_BUILDER_REQUEST_FOR_SHOW_COMPONENTS_TREE, this.showTree.bind(this));
        eventBus.on(EVENT_PAGE_BUILDER_REQUEST_FOR_EDIT_COMPONENT, this.showComponentForm.bind(this));
    }

    private getPanel(name: string): HTMLElement | undefined {
        return this.panelTargets.find((p) => p.dataset.id === name);
    }

    private showPanel(name: string): void {
        this.panelTargets.forEach((p) => {
            p.style.display = 'none';
        });

        const panel = this.getPanel(name);
        panel && (panel.style.display = 'block');
    }

    private addToContent(event?: PageBuilderRequestForAddToContentEvent): void {
        if (!event) {
            return;
        }

        this.event = event;
        this.showPanel('componentsList');
    }

    public showTree(): void {
        this.event = undefined;
        this.showPanel('tree');
    }

    public showComponentForm(): void {
        this.showPanel('componentForm');
    }

    public select(event: MouseEvent): void {
        const { insertComponentInTree } = usePageBuilderHelpers();
        const shortcode = (event.currentTarget as HTMLElement).dataset.shortcode!;

        if (
            !this.event?.tree ||
            !this.availableComponents ||
            !Object.prototype.hasOwnProperty.call(this.availableComponents, shortcode)
        ) {
            return;
        }

        const componentToInsert: PageComponent = JSON.parse(JSON.stringify(this.availableComponents[shortcode]));
        componentToInsert.id = uuidv4();

        const tree = insertComponentInTree(
            componentToInsert,
            this.event.tree,
            this.event.target?.id,
            this.event.position,
        );

        eventBus.emit(EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_COMPONENTS_TREE, {
            tree: JSON.parse(JSON.stringify(tree)),
        });

        this.showTree();
    }
}
