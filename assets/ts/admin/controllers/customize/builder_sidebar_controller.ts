/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from 'stimulus';
import { createApp } from 'vue';
import PageBuilderComponentsTree from 'admin/vue/components/builder/PageBuilderComponentsTree.vue';
import { eventBus } from 'admin/admin';
import PageBuilderRequestForAddToContentEvent from 'admin/events/PageBuilderRequestForAddToContentEvent';
import {
    EVENT_PAGE_BUILDER_COMPONENTS_LOADED,
    EVENT_PAGE_BUILDER_REQUEST_FOR_ADD_TO_CONTENT,
    EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_COMPONENTS_TREE,
    EVENT_PAGE_BUILDER_REQUEST_FOR_SHOW_COMPONENTS_TREE,
} from 'admin/events/events';
import PageBuilderRequestForShowComponentsTreeEvent from 'admin/events/PageBuilderRequestForShowComponentsTreeEvent';
import PageBuilderComponentsComponentsLoadedEvent from 'admin/events/PageBuilderComponentsComponentsLoadedEvent';
import GenericObject from 'admin/interfaces/GenericObject';
import PageComponent from 'admin/interfaces/PageComponent';
import usePageBuilderHelpers from 'admin/vue/functions/pageBuilderHelpers';
import { v4 as uuidv4 } from 'uuid';
import PageBuilderRequestForChangeComponentsTree from 'admin/events/PageBuilderRequestForChangeComponentsTree';

export default class extends Controller {
    public static targets = ['tree', 'componentsList'];

    private readonly treeTarget: HTMLElement;
    private readonly componentsListTarget: HTMLElement;

    private availableComponents?: GenericObject<PageComponent>;
    private event?: PageBuilderRequestForAddToContentEvent;

    public connect(): void {
        createApp(PageBuilderComponentsTree).mount(this.treeTarget);

        eventBus.on<PageBuilderComponentsComponentsLoadedEvent>(EVENT_PAGE_BUILDER_COMPONENTS_LOADED, (event) => {
            this.availableComponents = event?.availableComponents;
        });

        eventBus.on<PageBuilderRequestForAddToContentEvent>(
            EVENT_PAGE_BUILDER_REQUEST_FOR_ADD_TO_CONTENT,
            this.addToContent.bind(this),
        );

        eventBus.on<PageBuilderRequestForShowComponentsTreeEvent>(
            EVENT_PAGE_BUILDER_REQUEST_FOR_SHOW_COMPONENTS_TREE,
            this.showTree.bind(this),
        );
    }

    private addToContent(event?: PageBuilderRequestForAddToContentEvent): void {
        if (!event) {
            return;
        }

        this.event = event;
        this.showComponentsList();
    }

    private showComponentsList(): void {
        this.treeTarget.style.display = 'none';
        this.componentsListTarget.style.display = 'block';
    }

    private showTree(): void {
        this.event = undefined;
        this.treeTarget.style.display = 'block';
        this.componentsListTarget.style.display = 'none';
    }

    public select(event: MouseEvent): void {
        const { insertComponentInTree } = usePageBuilderHelpers();
        const shortcode = (event.currentTarget as HTMLElement).dataset.shortcode!;

        if (
            !this.event?.tree ||
            !this.availableComponents ||
            !Object.hasOwnProperty.call(this.availableComponents, shortcode)
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

        eventBus.emit<PageBuilderRequestForChangeComponentsTree>(
            EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_COMPONENTS_TREE,
            {
                tree: JSON.parse(JSON.stringify(tree)),
            },
        );

        this.showTree();
    }
}
