/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from 'stimulus';
import ParsedFile from 'src/admin/interfaces/ParsedFile';
import ImageResizer from 'src/admin/services/ImageResizer';

export default class extends Controller {
    public static targets = ['submit'];

    private readonly submitTarget: HTMLButtonElement;

    private files: ParsedFile[] = [];
    private dragCounter = 0;
    private isDraggingOver = false;

    public onDragOver(event): void {
        event.preventDefault();
        // console.log('onDragOver');
    }

    public onDragEnter(event: DragEvent): void {
        event.preventDefault();

        if (!this.supportsDragAndDrop()) {
            return;
        }

        this.dragCounter++;
        this.isDraggingOver = true;
    }

    public onDragLeave(event: DragEvent): void {
        event.preventDefault();

        if (!this.supportsDragAndDrop()) {
            return;
        }

        this.dragCounter--;

        if (this.dragCounter <= 0) {
            this.dragCounter = 0;
            this.isDraggingOver = false;
        }
    }

    public async onDrop(event: DragEvent): Promise<void> {
        event.preventDefault();
        console.log('File(s) dropped');
        this.onDragLeave(event);

        console.log(event);

        // Only add new files when we actually drop new files and not only sorting elements
        if (
            (event.target && Object.keys(event.target.files).length > 0) ||
            (event.dataTransfer && Object.keys(event.dataTransfer.files).length > 0)
        ) {
            const files: File[] = event.target?.files || event.dataTransfer?.files || [];
            const parsedFiles: ParsedFile[] = [];

            for (const file of files) {
                if (this.files.find((f) => f.file.name === file.name)) {
                    continue;
                }

                try {
                    parsedFiles.push(await this.parseFile(file));
                } catch (e) {
                    parsedFiles.push({ file, image: document.createElement('img') });
                }
            }

            this.files.push(...parsedFiles);
            //
            // if (this.autoUpload) {
            //     this.uploadAll();
            // }
        }

        // Must reset value in order to fire @change even if we select the same file
        // this.fileInput.value = '';
    }

    /**
     * Reads a file and resize if needed
     * @param file
     */
    private async parseFile(file: File): Promise<ParsedFile> {
        if (file.type.substr(0, 5) === 'image') {
            const image = await ImageResizer.createImageDataURLFromFile(file);
            const thumbnail = ImageResizer.resizeImage(image, { width: 250, height: 250, quality: 60 });

            return { file, image, thumbnail };
        }

        return { file };
    }

    private supportsDragAndDrop(): boolean {
        const div = document.createElement('div');
        return (
            ('draggable' in div || ('ondragstart' in div && 'ondrop' in div)) &&
            !('ontouchstart' in window || navigator.msMaxTouchPoints)
        );
    }
}
