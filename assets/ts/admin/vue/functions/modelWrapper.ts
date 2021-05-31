/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { computed, WritableComputedRef } from 'vue';

export default function useModelWrapper<T, K extends T[keyof T]>(
    props: T,
    emit: (event: string, value: any) => void,
    name = 'modelValue',
): WritableComputedRef<K> {
    return computed({
        get: () => props[name] as K,
        set: (value) => emit(`update:${name}`, value),
    });
}
