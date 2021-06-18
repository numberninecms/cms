/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import PageComponent from 'admin/interfaces/PageComponent';

interface PageBuilderHelpers {
    prepareTree: (tree: PageComponent[], parent?: PageComponent) => void;
}

export default function usePageBuilderHelpers(): PageBuilderHelpers {
    function prepareTree(tree: PageComponent[], parent?: PageComponent) {
        tree.forEach((component) => {
            component.parentId = parent ? parent.id : undefined;
            component.computed = {};

            if (component.children) {
                prepareTree(component.children, component);
            }
        });
    }

    return {
        prepareTree,
    };
}
