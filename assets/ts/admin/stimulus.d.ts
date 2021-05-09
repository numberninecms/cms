/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare module '@symfony/stimulus-bridge' {
    import { Application } from 'stimulus';
    export function startStimulusApp(context?: __WebpackModuleApi.RequireContext): Application;
}
