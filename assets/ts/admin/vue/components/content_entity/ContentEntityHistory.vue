<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div v-if="revisions.length > 0" class="flex flex-col">
        <div class="flex items-center gap-5 px-3 mt-12">
            <vue-slider
                v-model="currentRevisionStep"
                class="flex-grow"
                :min="0"
                :max="revisions.length - 1"
                :interval="1"
                adsorb
                contained
                drag-on-click
                marks
                hide-label
                :tooltip-formatter="dateFormatter"
            />
            <button
                type="button"
                class="btn btn-color-red"
                :disabled="currentRevisionStep === revisions.length - 1"
                @click="revert"
            >
                Revert
            </button>
        </div>
        <div class="mt-10">
            Viewing revision <strong>{{ currentRevisionVersion }}</strong> from
            <strong>{{ currentRevisionDate }}</strong>
        </div>

        <ContentEntityHistoryFieldDiff
            title="Title"
            field="title"
            :version="currentRevisionVersion"
            :revisions="revisions"
        />
        <ContentEntityHistoryFieldDiff
            title="Slug"
            field="slug"
            :version="currentRevisionVersion"
            :revisions="revisions"
        />
        <ContentEntityHistoryFieldDiff
            title="Excerpt"
            field="excerpt"
            :version="currentRevisionVersion"
            :revisions="revisions"
        />
        <ContentEntityHistoryFieldDiff
            title="Content"
            field="content"
            :version="currentRevisionVersion"
            :revisions="revisions"
        />
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, ref } from 'vue';
import VueSlider from 'vue-slider-component';
import 'vue-slider-component/theme/antd.css';
import 'vue-diff/dist/index.css';
import Routing from 'assets/ts/routing';
import dateFormat from 'dateformat';
import axios from 'axios';
import Revision from 'admin/interfaces/Revision';
import ContentEntityHistoryFieldDiff from 'admin/vue/components/content_entity/ContentEntityHistoryFieldDiff.vue';
import { EVENT_CONTENT_ENTITY_REVERT_TO_REVISION } from 'admin/events/events';
import { eventBus } from 'admin/admin';

export default defineComponent({
    name: 'ContentEntityHistory',
    components: { ContentEntityHistoryFieldDiff, VueSlider },
    props: {
        entityId: {
            type: Number,
            required: true,
        },
        contentType: {
            type: String,
            required: true,
        },
    },
    setup(props) {
        const currentRevisionStep = ref(0);
        const revisions = ref([] as Revision[]);

        onMounted(async () => {
            const response = await axios.get(
                Routing.generate('numbernine_admin_contententity_revisions_get_collection', {
                    type: props.contentType,
                    id: props.entityId,
                }),
            );

            revisions.value = response.data;
            currentRevisionStep.value = revisions.value.length - 1;
        });

        const currentRevisionVersion = computed(() => {
            if (currentRevisionStep.value > revisions.value.length - 1) {
                return 0;
            }

            return revisions.value[revisions.value.length - currentRevisionStep.value - 1].version;
        });

        const dateFormatter = () => {
            const revisionDate = revisions.value.find((r) => r.version === currentRevisionVersion.value)?.date;
            return revisionDate
                ? dateFormat(revisionDate, 'mmmm d, yyyy HH:MM')
                : currentRevisionVersion.value.toString();
        };

        const currentRevisionDate = computed(() => dateFormatter());

        function revert(): void {
            eventBus.emit(EVENT_CONTENT_ENTITY_REVERT_TO_REVISION, {
                contentType: props.contentType,
                entityId: props.entityId,
                version: currentRevisionVersion.value,
                date: currentRevisionDate.value,
            });
        }

        return {
            currentRevisionStep,
            currentRevisionVersion,
            currentRevisionDate,
            dateFormatter,
            revisions,
            revert,
        };
    },
});
</script>
