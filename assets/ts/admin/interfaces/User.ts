/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import Role from 'admin/interfaces/Role';
import GenericObject from 'admin/interfaces/GenericObject';

export default interface User {
    id?: number;
    username: string;
    password?: string;
    firstName?: string;
    lastName?: string;
    displayName?: string;
    displayNameFormat?: string;
    email?: string;
    userRoles: Role[];
    postsCount?: number;
    customFields?: GenericObject<any>;
}
