/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import mitt from 'mitt';
import 'styles/admin/admin.scss';
import 'images/NumberNineWithoutText.png';
import 'images/NumberNine512_slogan.png';
import { startStimulusApp } from '@symfony/stimulus-bridge';
import { Events } from 'admin/events/events';

export const app = startStimulusApp(
    require.context('@symfony/stimulus-bridge/lazy-controller-loader!./controllers', true, /\.([jt])sx?$/),
);

export const eventBus = mitt<Events>();
