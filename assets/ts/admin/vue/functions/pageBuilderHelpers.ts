/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import PageComponent from 'admin/interfaces/PageComponent';
import { v4 as uuidv4 } from 'uuid';
import { DropPosition } from 'admin/types/DropPosition';

interface PageBuilderHelpers {
    findComponentInTree: (id: string, components: PageComponent[]) => PageComponent | undefined;
    prepareTree: (tree: PageComponent[], parent?: PageComponent) => void;
    removeComponentInTree: (tree: PageComponent[], componentToRemoveId: string) => PageComponent[];
    duplicateComponentInTree: (tree: PageComponent[], componentToDuplicate: PageComponent) => PageComponent[];
    insertComponentInTree: (
        componentToInsert: PageComponent,
        tree: PageComponent[],
        siblingId?: string,
        position?: DropPosition,
    ) => PageComponent[];
}

export default function usePageBuilderHelpers(): PageBuilderHelpers {
    function findComponentInTree(id: string, components: PageComponent[]): PageComponent | undefined {
        for (const component of components) {
            if (component.id === id) {
                return component;
            }

            if (Object.hasOwnProperty.call(component, 'children') && component.children.length > 0) {
                const found = findComponentInTree(id, component.children);

                if (found) {
                    return found;
                }
            }
        }

        return undefined;
    }

    function assignNewUidToTree(tree: PageComponent[]) {
        tree.forEach((pageComponent) => {
            pageComponent.id = uuidv4();

            if (pageComponent.children && pageComponent.children.length > 0) {
                assignNewUidToTree(pageComponent.children);
            }
        });
    }

    function prepareTree(tree: PageComponent[], parent?: PageComponent) {
        tree.forEach((component) => {
            component.parentId = parent ? parent.id : undefined;
            component.computed = {};

            if (component.children) {
                prepareTree(component.children, component);
            }
        });
    }

    function removeComponentInTree(tree: PageComponent[], componentToRemoveId: string): PageComponent[] {
        for (const [i, component] of tree.entries()) {
            if (component.id === componentToRemoveId) {
                tree.splice(i, 1);
                return tree;
            } else if (component.children && component.children.length > 0) {
                component.children = removeComponentInTree(component.children, componentToRemoveId);
            }
        }

        return tree;
    }

    function duplicateComponentInTree(tree: PageComponent[], componentToDuplicate: PageComponent): PageComponent[] {
        for (const [i, component] of tree.entries()) {
            if (component.id === componentToDuplicate.id) {
                const newComponent: PageComponent = Object.assign({}, componentToDuplicate);
                assignNewUidToTree([newComponent]);
                tree.splice(i + 1, 0, newComponent);
                return tree;
            } else if (component.children && component.children.length > 0) {
                component.children = duplicateComponentInTree(component.children, componentToDuplicate);
            }
        }

        return tree;
    }

    function insertComponentInTree(
        componentToInsert: PageComponent,
        tree: PageComponent[],
        siblingId?: string,
        position?: DropPosition,
    ): PageComponent[] {
        if (!position) {
            position = 'bottom';
        }

        if (!siblingId) {
            if (['bottom', 'right'].includes(position)) {
                tree.push(componentToInsert);
            } else {
                tree.splice(0, 0, componentToInsert);
            }

            return tree;
        }

        for (const [i, component] of tree.entries()) {
            if (component.id === siblingId) {
                const increment = ['bottom', 'right'].includes(position) ? 1 : 0;
                tree.splice(i + increment, 0, componentToInsert);
                return tree;
            } else if (component.children && component.children.length > 0) {
                component.children = insertComponentInTree(componentToInsert, component.children, siblingId, position);
            }
        }

        return tree;
    }

    return {
        findComponentInTree,
        prepareTree,
        removeComponentInTree,
        duplicateComponentInTree,
        insertComponentInTree,
    };
}
