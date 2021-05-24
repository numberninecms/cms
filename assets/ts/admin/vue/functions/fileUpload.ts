/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Ref, ref } from 'vue';
import ImageResizer from '../../services/ImageResizer';
import ParsedFile from '../../interfaces/ParsedFile';
import ResizeOptions from '../../interfaces/ResizeOptions';
import axios, { AxiosPromise } from 'axios';

interface Options {
    uploadUrl: string;
    maxUploadSize: number;
    autoUpload?: boolean;
    resizeOptions?: ResizeOptions;
    sequential?: boolean;
    onFileUploaded?: (payload: { file: ParsedFile; data: any }) => void;
}

interface FileUpload {
    files: Ref<ParsedFile[]>;
    queueFilesForUpload: (files: File[]) => Promise<void>;
    startUpload: () => Promise<void>;
}

export default function useFileUpload(options: Options): FileUpload {
    const files: Ref<ParsedFile[]> = ref([]);

    async function queueFilesForUpload(filesToQueue: File[]): Promise<void> {
        const parsedFiles: ParsedFile[] = [];

        for (const file of filesToQueue) {
            if (files.value.find((f) => f.file.name === file.name)) {
                continue;
            }

            try {
                parsedFiles.push(await parseFile(file));
            } catch (e) {
                parsedFiles.push({ file, image: document.createElement('img') });
            }
        }

        files.value.push(...parsedFiles);

        if (options.autoUpload) {
            await startUpload();
        }
    }

    async function parseFile(file: File): Promise<ParsedFile> {
        if (file.type.substr(0, 5) === 'image') {
            const image = await ImageResizer.createImageDataURLFromFile(file);
            const thumbnail = ImageResizer.resizeImage(image, { width: 250, height: 250, quality: 60 });

            return { file, image, thumbnail };
        }

        return { file };
    }

    async function startUpload(): Promise<void> {
        for (const file of [...files.value]) {
            if (
                file.image &&
                options.resizeOptions &&
                options.resizeOptions.enabled &&
                (options.resizeOptions.width < file.image.width || options.resizeOptions.height < file.image.height)
            ) {
                file.image = ImageResizer.resizeImage(file.image, options.resizeOptions);

                if (ImageResizer.getImageFileSize(file.image) >= options.maxUploadSize) {
                    file.error = 'file_too_big';

                    if (options.sequential) {
                        return;
                    }

                    continue;
                }
            } else if (file.file.size >= options.maxUploadSize) {
                file.error = 'file_too_big';

                if (options.sequential) {
                    return;
                }

                continue;
            }

            if (!options.sequential) {
                void asyncUploadFile(file)
                    .then((response) => {
                        if (response && options.onFileUploaded) {
                            options.onFileUploaded({ file, data: response.data });
                        }
                    })
                    .catch(() => {
                        file.error = 'upload_error';
                    });
            } else {
                try {
                    const response = await asyncUploadFile(file);

                    if (response && options.onFileUploaded) {
                        options.onFileUploaded({ file, data: response.data });
                    }
                } catch (e) {
                    file.error = 'upload_error';
                    return;
                }
            }
        }
    }

    function asyncUploadFile(file: ParsedFile): AxiosPromise {
        const formData = new FormData();

        if (file.image) {
            const imageFile = new File([convertDataUriToBlob(file.image.src)], file.file.name, {
                type: file.file.type,
            });
            formData.append('file', imageFile);
        } else {
            formData.append('file', file.file);
        }

        return axios.post(options.uploadUrl, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            maxContentLength: options.maxUploadSize,
            onUploadProgress: (progressEvent: ProgressEvent) => {
                files.value[files.value.indexOf(file)].uploadProgress = progressEvent.loaded / progressEvent.total;
            },
        });
    }

    function convertDataUriToBlob(dataUri: string): Blob {
        let byteString: string;
        if (dataUri.split(',')[0].indexOf('base64') >= 0) {
            byteString = atob(dataUri.split(',')[1]);
        } else {
            byteString = unescape(dataUri.split(',')[1]);
        }

        // separate out the mime component
        const mimeString = dataUri.split(',')[0].split(':')[1].split(';')[0];

        // write the bytes of the string to a typed array
        const ia = new Uint8Array(byteString.length);
        for (let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }

        return new Blob([ia], { type: mimeString });
    }

    return {
        files,
        queueFilesForUpload,
        startUpload,
    };
}
