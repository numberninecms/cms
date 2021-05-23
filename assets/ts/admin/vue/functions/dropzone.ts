/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { ref, Ref } from 'vue';

interface Dropzone {
    isDraggingOver: Ref<boolean>;
    onDragEnter: (event: DragEvent) => void;
    onDragLeave: (event: DragEvent) => void;
}

export default function useDropzone(): Dropzone {
    const dragCounter = ref(0);
    const isDraggingOver = ref(false);

    function onDragEnter(event: DragEvent): void {
        event.preventDefault();

        if (!supportsDragAndDrop()) {
            return;
        }

        dragCounter.value++;
        isDraggingOver.value = true;
    }

    function onDragLeave(event: DragEvent): void {
        event.preventDefault();

        if (!supportsDragAndDrop()) {
            return;
        }

        dragCounter.value--;

        if (dragCounter.value <= 0) {
            dragCounter.value = 0;
            isDraggingOver.value = false;
        }
    }

    function supportsDragAndDrop(): boolean {
        const div = document.createElement('div');
        return (
            ('draggable' in div || ('ondragstart' in div && 'ondrop' in div)) &&
            !('ontouchstart' in window || navigator.msMaxTouchPoints)
        );
    }

    return {
        isDraggingOver,
        onDragEnter,
        onDragLeave,
    };
}
