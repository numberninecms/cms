/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import EXIF from 'exif-js/exif';
import ResizeOptions from 'admin/interfaces/ResizeOptions';
import ExifDescriptor from 'admin/interfaces/ExifDescriptor';

export default class ImageResizer {
    /**
     * Reads a file as a data URL and returns it as promise
     */
    public static createImageDataURLFromFile(file: File): Promise<HTMLImageElement> {
        const reader = new FileReader();

        return new Promise<HTMLImageElement>((resolve, reject) => {
            reader.onerror = () => {
                reject(new DOMException('Problem parsing input file.'));
            };

            reader.onload = async (e: ProgressEvent<FileReader>) => {
                const image: HTMLImageElement = document.createElement('img');
                image.src = e.target?.result as string;

                const exif = EXIF.readFromBinaryFile(
                    ImageResizer.base64ToArrayBuffer(e.target?.result as string),
                ) as ExifDescriptor;

                if (exif && Object.prototype.hasOwnProperty.call(exif, 'Orientation')) {
                    image.src = await ImageResizer.rotateBase64Image(image.src, exif.Orientation);
                }

                resolve(image);
            };

            reader.readAsDataURL(file);
        });
    }

    public static resizeImage(image: HTMLImageElement, resize: ResizeOptions): HTMLImageElement {
        const dimensions = ImageResizer.calculateScaleDimensions(
            image.width,
            image.height,
            resize.width,
            resize.height,
        );
        const scaledCanvas = ImageResizer.downscaleImage(image, dimensions.ratio);

        const scaledImage = document.createElement('img');
        scaledImage.src = scaledCanvas.toDataURL(
            resize.mimeType || 'image/WebP',
            resize.quality ? resize.quality / 100 : 0.934,
        );
        return scaledImage;
    }

    public static getImageFileSize(image: HTMLImageElement): number {
        const base64: string = image.src.replace(/^data:([^;]+);base64,/gim, '');
        const codeSize = (base64.length * 4) / 3;
        const paddingSize = base64.length % 3 ? 3 - (base64.length % 3) : 0;
        const crlfsSize = 2 + (2 * (codeSize + paddingSize)) / 72;

        return codeSize + paddingSize + crlfsSize;
    }

    private static calculateScaleDimensions(
        srcWidth,
        srcHeight,
        maxWidth,
        maxHeight,
    ): { ratio: number; width: number; height: number } {
        const ratio = Math.min(maxWidth / srcWidth, maxHeight / srcHeight);

        return {
            ratio,
            width: srcWidth * ratio,
            height: srcHeight * ratio,
        };
    }

    // scales the image by (float) scale < 1
    // returns a canvas containing the scaled image.
    private static downscaleImage(image: HTMLImageElement, scale: number): HTMLCanvasElement {
        const canvas = document.createElement('canvas');
        canvas.width = image.width;
        canvas.height = image.height;
        const context = canvas.getContext('2d');

        if (context) {
            context.drawImage(image, 0, 0);
            return ImageResizer.downscaleCanvas(canvas, context, scale);
        }

        throw new Error('Error while resizing image.');
    }

    // scales the canvas by (float) scale < 1
    // returns a new canvas containing the scaled image.
    private static downscaleCanvas(
        canvas: HTMLCanvasElement,
        context: CanvasRenderingContext2D,
        scale: number,
    ): HTMLCanvasElement {
        if (!(scale < 1) || !(scale > 0)) {
            throw new Error('Scale must be a positive number <1');
        }

        const squareScale = scale * scale;
        const sourceWidth = canvas.width;
        const sourceHeight = canvas.height;
        const targetWidth = Math.floor(sourceWidth * scale);
        const targetHeight = Math.floor(sourceHeight * scale);
        let sourceX = 0;
        let sourceY = 0;
        let sourceIndex = 0;
        let targetX = 0;
        let targetY = 0;
        let targetXIndex = 0;
        let targetYIndex = 0;
        let roundedTargetX = 0;
        let roundedTargetY = 0;
        let weight = 0;
        let nextWeight = 0;
        let weightX = 0;
        let nextWeightX = 0;
        let weightY = 0;
        let nextWeightY = 0;

        // weight is weight of current source point within target.
        // next weight is weight of current source point within next target's point.
        let crossX = false; // does scaled px cross its current px right border ?
        let crossY = false; // does scaled px cross its current px bottom border ?
        const sourceBuffer = context.getImageData(0, 0, sourceWidth, sourceHeight).data; // source buffer 8 bit rgba
        const targetBuffer = new Float32Array(3 * targetWidth * targetHeight); // target buffer Float32 rgb
        let sR = 0;
        let sG = 0;
        let sB = 0; // source's current point r,g,b
        /* untested !
         var sA = 0;  //source alpha  */

        for (sourceY = 0; sourceY < sourceHeight; sourceY++) {
            targetY = sourceY * scale; // y src position within target
            roundedTargetY = Math.floor(targetY); // rounded : target pixel's y
            targetXIndex = 3 * roundedTargetY * targetWidth; // line index within target array
            crossY = roundedTargetY !== Math.floor(targetY + scale);
            if (crossY) {
                // if pixel is crossing botton target pixel
                weightY = roundedTargetY + 1 - targetY; // weight of point within target pixel
                nextWeightY = targetY + scale - roundedTargetY - 1; // ... within y+1 target pixel
            }
            for (sourceX = 0; sourceX < sourceWidth; sourceX++, sourceIndex += 4) {
                targetX = sourceX * scale; // x src position within target
                roundedTargetX = Math.floor(targetX); // rounded : target pixel's x
                targetYIndex = targetXIndex + roundedTargetX * 3; // target pixel index within target array
                crossX = roundedTargetX !== Math.floor(targetX + scale);
                if (crossX) {
                    // if pixel is crossing target pixel's right
                    weightX = roundedTargetX + 1 - targetX; // weight of point within target pixel
                    nextWeightX = targetX + scale - roundedTargetX - 1; // ... within x+1 target pixel
                }
                sR = sourceBuffer[sourceIndex]; // retrieving r,g,b for curr src px.
                sG = sourceBuffer[sourceIndex + 1];
                sB = sourceBuffer[sourceIndex + 2];

                /* !! untested : handling alpha !!
                 sA = sBuffer[sIndex + 3];
                 if (!sA) continue;
                 if (sA !== 0xFF) {
                 sR = (sR * sA) >> 8;  // or use /256 instead ??
                 sG = (sG * sA) >> 8;
                 sB = (sB * sA) >> 8;
                 }
                 */
                if (!crossX && !crossY) {
                    // pixel does not cross
                    // just add components weighted by squared scale.
                    targetBuffer[targetYIndex] += sR * squareScale;
                    targetBuffer[targetYIndex + 1] += sG * squareScale;
                    targetBuffer[targetYIndex + 2] += sB * squareScale;
                } else if (crossX && !crossY) {
                    // cross on X only
                    weight = weightX * scale;
                    // add weighted component for current px
                    targetBuffer[targetYIndex] += sR * weight;
                    targetBuffer[targetYIndex + 1] += sG * weight;
                    targetBuffer[targetYIndex + 2] += sB * weight;
                    // add weighted component for next (tX+1) px
                    nextWeight = nextWeightX * scale;
                    targetBuffer[targetYIndex + 3] += sR * nextWeight;
                    targetBuffer[targetYIndex + 4] += sG * nextWeight;
                    targetBuffer[targetYIndex + 5] += sB * nextWeight;
                } else if (crossY && !crossX) {
                    // cross on Y only
                    weight = weightY * scale;
                    // add weighted component for current px
                    targetBuffer[targetYIndex] += sR * weight;
                    targetBuffer[targetYIndex + 1] += sG * weight;
                    targetBuffer[targetYIndex + 2] += sB * weight;
                    // add weighted component for next (tY+1) px
                    nextWeight = nextWeightY * scale;
                    targetBuffer[targetYIndex + 3 * targetWidth] += sR * nextWeight;
                    targetBuffer[targetYIndex + 3 * targetWidth + 1] += sG * nextWeight;
                    targetBuffer[targetYIndex + 3 * targetWidth + 2] += sB * nextWeight;
                } else {
                    // crosses both x and y : four target points involved
                    // add weighted component for current px
                    weight = weightX * weightY;
                    targetBuffer[targetYIndex] += sR * weight;
                    targetBuffer[targetYIndex + 1] += sG * weight;
                    targetBuffer[targetYIndex + 2] += sB * weight;
                    // for tX + 1; tY px
                    nextWeight = nextWeightX * weightY;
                    targetBuffer[targetYIndex + 3] += sR * nextWeight;
                    targetBuffer[targetYIndex + 4] += sG * nextWeight;
                    targetBuffer[targetYIndex + 5] += sB * nextWeight;
                    // for tX ; tY + 1 px
                    nextWeight = weightX * nextWeightY;
                    targetBuffer[targetYIndex + 3 * targetWidth] += sR * nextWeight;
                    targetBuffer[targetYIndex + 3 * targetWidth + 1] += sG * nextWeight;
                    targetBuffer[targetYIndex + 3 * targetWidth + 2] += sB * nextWeight;
                    // for tX + 1 ; tY +1 px
                    nextWeight = nextWeightX * nextWeightY;
                    targetBuffer[targetYIndex + 3 * targetWidth + 3] += sR * nextWeight;
                    targetBuffer[targetYIndex + 3 * targetWidth + 4] += sG * nextWeight;
                    targetBuffer[targetYIndex + 3 * targetWidth + 5] += sB * nextWeight;
                }
            } // end for sx
        } // end for sy

        // create result canvas
        const resultCanvas = document.createElement('canvas');
        resultCanvas.width = targetWidth;
        resultCanvas.height = targetHeight;
        const resultContext = resultCanvas.getContext('2d');

        if (!resultContext) {
            throw new Error('Error downscaling image.');
        }

        const resultImage = resultContext.getImageData(0, 0, targetWidth, targetHeight);
        const targetByteBuffer = resultImage.data;
        // convert float32 array into a UInt8Clamped Array
        let pxIndex = 0; //
        for (
            sourceIndex = 0, targetYIndex = 0;
            pxIndex < targetWidth * targetHeight;
            sourceIndex += 3, targetYIndex += 4, pxIndex++
        ) {
            targetByteBuffer[targetYIndex] = Math.ceil(targetBuffer[sourceIndex]);
            targetByteBuffer[targetYIndex + 1] = Math.ceil(targetBuffer[sourceIndex + 1]);
            targetByteBuffer[targetYIndex + 2] = Math.ceil(targetBuffer[sourceIndex + 2]);
            targetByteBuffer[targetYIndex + 3] = 255;
        }
        // writing result to canvas.
        resultContext.putImageData(resultImage, 0, 0);

        return resultCanvas;
    }

    /**
     * @param base64
     */
    private static base64ToArrayBuffer(base64: string) {
        base64 = base64.replace(/^data:([^;]+);base64,/gim, '');
        const binaryString = atob(base64);
        const len = binaryString.length;
        const bytes = new Uint8Array(len);

        for (let i = 0; i < len; i++) {
            bytes[i] = binaryString.charCodeAt(i);
        }

        return bytes.buffer;
    }

    /**
     * @param base64Image
     * @param orientation
     */
    private static rotateBase64Image(base64Image: string, orientation: number): Promise<string> {
        if ([3, 6, 8].indexOf(orientation) === -1) {
            return new Promise<string>((resolve) => {
                resolve(base64Image);
            });
        }

        // create an off-screen canvas
        const offScreenCanvas = document.createElement('canvas');
        const offScreenCanvasCtx = offScreenCanvas.getContext('2d');

        return new Promise<string>((resolve) => {
            // create Image
            const img = new Image();

            img.onload = function () {
                // set its dimension to rotated size
                if (orientation === 6 || orientation === 8) {
                    // noinspection JSSuspiciousNameCombination
                    offScreenCanvas.height = img.width;
                    // noinspection JSSuspiciousNameCombination
                    offScreenCanvas.width = img.height;
                } else if (orientation === 3) {
                    offScreenCanvas.width = img.width;
                    offScreenCanvas.height = img.height;
                }

                // rotate and draw source image into the off-screen canvas:
                if (orientation === 6) {
                    offScreenCanvasCtx!.rotate((90 * Math.PI) / 180);
                    offScreenCanvasCtx!.translate(0, -offScreenCanvas.width);
                } else if (orientation === 8) {
                    offScreenCanvasCtx!.rotate((-90 * Math.PI) / 180);
                    offScreenCanvasCtx!.translate(-offScreenCanvas.height, 0);
                } else if (orientation === 3) {
                    offScreenCanvasCtx!.rotate(Math.PI);
                }

                offScreenCanvasCtx!.drawImage(img, 0, 0);

                // encode image to data-uri with base64
                resolve(offScreenCanvas.toDataURL('image/jpeg', 100));
            };

            img.src = base64Image;
        });
    }
}
