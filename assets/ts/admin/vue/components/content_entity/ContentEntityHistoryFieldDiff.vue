<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div v-if="hasFieldValueSinceVersion(field, version)">
        <p class="font-semibold mt-3 mb-2">
            {{ title }}
            <span v-if="previous === current" class="bg-green-500 text-white rounded text-xs px-1">no change</span>
        </p>
        <Diff
            v-if="previous !== current"
            class="border border-gray-300"
            mode="unified"
            theme="light"
            language="plaintext"
            :prev="previous"
            :current="current"
        />
        <!-- eslint-disable vue/no-v-html -->
        <div v-else v-html="current"></div>
        <!--eslint-enable-->
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, PropType } from 'vue';
import Revision from 'admin/interfaces/Revision';

export default defineComponent({
    name: 'ContentEntityHistoryFieldDiff',
    props: {
        title: {
            type: String,
            required: true,
        },
        field: {
            type: String,
            required: true,
        },
        version: {
            type: Number,
            required: true,
        },
        revisions: {
            type: Array as PropType<Revision[]>,
            required: true,
        },
    },
    setup(props) {
        function getFieldForVersion(fieldName: string, version: number): string {
            if (version <= 0) {
                return '';
            }

            const slice = props.revisions.slice(props.revisions.findIndex((r) => r.version === version));
            let fieldValue: string = slice[0][fieldName] ?? null;

            if (fieldValue) {
                return `${fieldValue}\n`;
            }

            for (let i = 1; i < slice.length; i++) {
                if (slice[i][fieldName]) {
                    return `${slice[i][fieldName] as string}\n`;
                }
            }

            return '';
        }

        function hasFieldValueSinceVersion(fieldName: string, version: number): boolean {
            const slice = props.revisions.slice(props.revisions.findIndex((r) => r.version === version));

            for (const revision of slice) {
                if (revision[fieldName]) {
                    return true;
                }
            }

            return false;
        }

        const previous = computed(() => getFieldForVersion(props.field, props.version - 1));
        const current = computed(() => getFieldForVersion(props.field, props.version));

        return {
            previous,
            current,
            hasFieldValueSinceVersion,
        };
    },
});
</script>
