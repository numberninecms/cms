/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import PageComponent from 'admin/interfaces/PageComponent';
import GenericObject from 'admin/interfaces/GenericObject';
import Form from 'admin/interfaces/Form';

export default interface PageBuilderComponentsComponentsLoadedEvent {
    tree: PageComponent[];
    availableComponents: GenericObject<PageComponent>;
    forms: GenericObject<Form>;
}
