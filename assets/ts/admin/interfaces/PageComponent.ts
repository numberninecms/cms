/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Direction } from 'admin/types/Direction';
import GenericObject from 'admin/interfaces/GenericObject';

export default interface PageComponent {
    id: string;
    name: string;
    parameters: GenericObject<any>;
    computed: GenericObject<any>;
    position: number;
    label: string;
    icon?: string;
    avatar?: string;
    children: PageComponent[];
    parentId: string | undefined;
    siblingsPosition: Direction[];
    siblingsShortcodes: string[];
    editable: boolean;
    container: boolean;
    visibility?: GenericObject<boolean>;
    responsive: string[];
}
