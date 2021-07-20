/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import MenuItem from 'admin/interfaces/MenuItem';

interface MenuHelpers {
    removeItemInTree: (tree: MenuItem[], itemToRemoveId: string) => MenuItem[];
}

export default function useMenuHelpers(): MenuHelpers {
    function removeItemInTree(tree: MenuItem[], itemToRemoveId: string): MenuItem[] {
        for (const [i, item] of tree.entries()) {
            if (item.uid === itemToRemoveId) {
                tree.splice(i, 1);
                return tree;
            } else if (item.children && item.children.length > 0) {
                item.children = removeItemInTree(item.children, itemToRemoveId);
            }
        }

        return tree;
    }

    return {
        removeItemInTree,
    };
}
