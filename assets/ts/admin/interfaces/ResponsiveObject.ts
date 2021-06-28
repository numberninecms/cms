/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { ViewportSize } from 'admin/types/ViewportSize';

export default interface ResponsiveObject<T> extends Record<ViewportSize, T> {}