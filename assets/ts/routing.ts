/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import routes from '../routing/routes.json';
import GenericObject from 'admin/interfaces/GenericObject';

interface Router {
    setRoutingData(routes: any): void;
    generate(route: string, params?: GenericObject<any>): string;
}

(Routing as Router).setRoutingData(routes);

export default Routing as Router;
