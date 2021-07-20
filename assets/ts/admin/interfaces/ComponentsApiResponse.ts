/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import PageComponent from 'admin/interfaces/PageComponent';
import Form from 'admin/interfaces/Form';
import GenericObject from 'admin/interfaces/GenericObject';

export default interface ComponentsApiResponse {
    tree: PageComponent[];
    components: GenericObject<PageComponent>;
    controls: GenericObject<Form>;
    templates: GenericObject<string>;
}
