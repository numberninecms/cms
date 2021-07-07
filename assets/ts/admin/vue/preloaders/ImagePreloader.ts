/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import PageComponent from 'admin/interfaces/PageComponent';
import Preloader from 'admin/interfaces/Preloader';
import MediaFile from 'admin/interfaces/MediaFile';
import { useContentEntityStore } from 'admin/vue/stores/contentEntity';
import { usePageBuilderStore } from 'admin/vue/stores/pageBuilder';

export class ImagePreloader implements Preloader {
    private readonly component: PageComponent;

    public constructor(component: PageComponent) {
        this.component = component;
    }

    public async preload(): Promise<void> {
        const contentEntityStore = useContentEntityStore();
        const pageBuilderStore = usePageBuilderStore();

        let value: MediaFile | undefined;

        if (this.component.parameters.id) {
            value = (await contentEntityStore.fetchSingleEntityById(
                this.component.parameters.id as number,
                'media_file',
            )) as MediaFile;
        } else if (this.component.parameters.fromTitle) {
            value = (await contentEntityStore.fetchSingleEntityByTitle(
                this.component.parameters.fromTitle as string,
                'media_file',
            )) as MediaFile;
        }

        if (value) {
            pageBuilderStore.updateComponentComputedParameter({
                id: this.component.id,
                parameter: 'image',
                value,
            });
        }
    }
}
