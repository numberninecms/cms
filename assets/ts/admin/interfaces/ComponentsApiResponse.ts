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

export default interface ComponentsApiResponse {
    tree: PageComponent[];
    components: { [componentName: string]: PageComponent };
    controls: { [componentName: string]: Form[] };
    templates: { [componentName: string]: string };
}
