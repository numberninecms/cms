/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import PageComponent from 'admin/interfaces/PageComponent';
import { DropPosition } from 'admin/types/DropPosition';

export default interface PageBuilderRequestForAddToContentEvent {
    tree?: PageComponent[];
    target?: PageComponent;
    position?: DropPosition;
}
