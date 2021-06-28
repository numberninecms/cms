<!--
  - This file is part of the NumberNine package.
  -
  - (c) William Arin <williamarin.dev@gmail.com>
  -
  - For the full copyright and license information, please view the LICENSE
  - file that was distributed with this source code.
  -->

<template>
    <div v-if="form" class="px-3 mt-2">
        <div class="text-xl font-bold mb-3">{{ component?.label }}</div>
        <component
            :is="`FormControl${control.name}`"
            v-for="(control, field) in form"
            :key="field"
            :value="fieldValue(field)"
            :parameters="control.parameters"
            class="mb-2"
            @input="updateParameter(field, $event)"
            @input-computed="updateComputed(field, $event)"
        />
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, Ref, ref } from 'vue';
import PageComponent from 'admin/interfaces/PageComponent';
import {
    EVENT_PAGE_BUILDER_COMPONENT_UPDATED,
    EVENT_PAGE_BUILDER_COMPONENTS_LOADED,
    EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_VIEWPORT_SIZE_EVENT,
    EVENT_PAGE_BUILDER_REQUEST_FOR_EDIT_COMPONENT,
} from 'admin/events/events';
import { eventBus } from 'admin/admin';
import GenericObject from 'admin/interfaces/GenericObject';
import Form from 'admin/interfaces/Form';
import { ViewportSize } from 'admin/types/ViewportSize';
import { Primitive } from 'admin/types/Primitive';
import { OverridablePrimitive } from 'admin/types/OverridablePrimitive';
import formControls from 'admin/vue/form/formControls';

export default defineComponent({
    name: 'PageBuilderComponentForm',
    components: formControls,
    setup() {
        const component: Ref<PageComponent | undefined> = ref(undefined);
        let forms: GenericObject<Form> | undefined;
        const form = computed(() => (component.value?.name && forms ? forms[component.value.name] : undefined));
        let viewportSize: ViewportSize = 'lg';

        onMounted(() => {
            eventBus.on(EVENT_PAGE_BUILDER_COMPONENTS_LOADED, (event) => {
                forms = event.forms;
            });

            eventBus.on(EVENT_PAGE_BUILDER_REQUEST_FOR_EDIT_COMPONENT, (event) => {
                component.value = event.component;
            });

            eventBus.on(EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_VIEWPORT_SIZE_EVENT, (size) => {
                viewportSize = size;
            });
        });

        function fieldValue(field: string): any {
            if (isResponsive(field)) {
                return Object.prototype.hasOwnProperty.call(component.value?.parameters[field], viewportSize)
                    ? component.value?.parameters[field]![viewportSize]
                    : '';
            }

            return component.value?.parameters[field] ?? '';
        }

        function isResponsive(field: string): boolean {
            return component.value?.responsive.includes(field) ?? false;
        }

        function isValueOverridden(value: OverridablePrimitive): boolean {
            return (
                typeof value === 'object' &&
                value !== null &&
                Object.prototype.hasOwnProperty.call(value, 'parameter') &&
                Object.prototype.hasOwnProperty.call(value, 'value')
            );
        }

        function updateParameter(parameter: string, value: OverridablePrimitive) {
            // A control can override which field will be updated instead of itself
            if (isValueOverridden(value)) {
                parameter = (value as { parameter: string; value: Primitive }).parameter;
                value = (value as { parameter: string; value: Primitive }).value;
            }

            if (isResponsive(parameter)) {
                component.value!.parameters[parameter] = Object.assign(component.value!.parameters[parameter], {
                    [viewportSize]: value,
                });
            } else {
                component.value!.parameters[parameter] = value as Primitive;
            }

            eventBus.emit(EVENT_PAGE_BUILDER_COMPONENT_UPDATED, {
                component: component.value!,
            });
        }

        return {
            component,
            form,
            fieldValue,
            updateParameter,
        };
    },
});
</script>