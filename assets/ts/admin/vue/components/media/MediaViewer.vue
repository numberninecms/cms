<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <teleport v-if="show && displayIndex !== -1" to="body">
        <div class="modal-backdrop" @click="closeModal">
            <div class="modal-backdrop-container">
                <div class="modal-card" @click.stop>
                    <div class="flex justify-end gap-1 p-3 shadow">
                        <button class="btn btn-color-white btn-style-outline" @click="navigatePrevious">
                            <i class="fa fa-chevron-left"></i>
                        </button>
                        <button class="btn btn-color-white btn-style-outline" @click="navigateNext">
                            <i class="fa fa-chevron-right"></i>
                        </button>
                        <button class="btn btn-color-white btn-style-outline" @click="closeModal">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    <div class="overflow-hidden flex flex-grow p-3">
                        <div class="flex flex-grow items-center md:w-3/4">
                            <img
                                v-if="imageUrl(mediaFile, 'large')"
                                :src="imageUrl(mediaFile, 'large')"
                                :alt="mediaFile.title"
                                class="object-scale-down w-full max-h-full"
                            />
                            <div v-else class="flex items-center justify-center">
                                <i class="fa fa-file text-primary text-7xl" />
                            </div>
                        </div>
                        <div class="hidden md:flex md:flex-grow md:w-1/4">
                            <MediaFileProperties :media-file="mediaFile" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </teleport>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, onUnmounted } from 'vue';
import useMediaFileUtilities from 'admin/vue/functions/mediaFileUtilities';
import MediaFileProperties from 'admin/vue/components/media/MediaFileProperties.vue';
import { useMediaFilesStore } from 'admin/vue/stores/mediaFiles';
import { useMediaViewerStore } from 'admin/vue/stores/mediaViewer';

export default defineComponent({
    name: 'MediaViewer',
    components: { MediaFileProperties },
    setup() {
        const mediaFilesStore = useMediaFilesStore();
        const mediaViewerStore = useMediaViewerStore();
        const { imageUrl } = useMediaFileUtilities();

        onMounted(() => {
            window.addEventListener('keydown', keyDownListener);
        });

        onUnmounted(() => {
            window.removeEventListener('keydown', keyDownListener);
        });

        function keyDownListener(event: KeyboardEvent) {
            if (event.key === 'ArrowLeft') {
                navigatePrevious();
            } else if (event.key === 'ArrowRight') {
                navigateNext();
            } else if (event.key === 'Escape') {
                closeModal();
            }
        }

        function navigatePrevious() {
            if (mediaViewerStore.displayIndex > 0) {
                mediaViewerStore.displayIndex--;
            } else {
                mediaViewerStore.displayIndex = mediaFilesStore.mediaFiles.length - 1;
            }
        }

        function navigateNext() {
            if (mediaViewerStore.displayIndex < mediaFilesStore.mediaFiles.length - 1) {
                mediaViewerStore.displayIndex++;
            } else {
                mediaViewerStore.displayIndex = 0;
            }
        }

        function closeModal() {
            mediaViewerStore.show = false;
        }

        return {
            imageUrl,
            displayIndex: computed(() => mediaViewerStore.displayIndex),
            show: computed(() => mediaViewerStore.show),
            mediaFile: computed(() => mediaFilesStore.mediaFiles[mediaViewerStore.displayIndex]),
            navigatePrevious,
            navigateNext,
            closeModal,
        };
    },
});
</script>
