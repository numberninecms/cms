/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import BaseRouting from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import routes from '../routing/routes.json';
import GenericObject from 'admin/interfaces/GenericObject';

interface Router {
    setRoutingData(routes: any): void;
    generate(route: string, params?: GenericObject<any>): string;
}

declare const n9_admin_url_prefix: string;

class DecoratedRouting implements Router {
    public constructor(private decoratedRouting: Router) {}

    public generate(route: string, params?: GenericObject<any>): string {
        return this.decoratedRouting.generate(route, params).replace(/^\/admin\//, `/${n9_admin_url_prefix}/`);
    }

    public setRoutingData(routes: any): void {
        return this.decoratedRouting.setRoutingData(routes);
    }
}

const Routing = new DecoratedRouting(BaseRouting);
Routing.setRoutingData(routes);

export default Routing as Router;
