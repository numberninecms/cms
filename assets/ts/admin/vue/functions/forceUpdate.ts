/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { nextTick, Ref, ref } from 'vue';
import { v4 as uuidv4 } from 'uuid';

interface ForceUpdate {
    uuid: Ref<string>;
    generate: () => void;
}

export default function useForceUpdate(): ForceUpdate {
    const uuid = ref(uuidv4());

    function generate(): void {
        void nextTick(() => {
            uuid.value = uuidv4();
        });
    }

    return {
        uuid,
        generate,
    };
}
