<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Reflection;

use NumberNine\Model\Inspector\InspectedRenderable;

trait ResetOwnPropertiesTrait
{
    private function reset(bool $isSerialization = false): void
    {
        /** @var InspectedRenderable $inspectedRenderable */
        $inspectedRenderable = $this->renderableInspector->inspect($this, $isSerialization);
        $defaultProperties = $inspectedRenderable->getDefaultProperties();

        foreach ($inspectedRenderable->getExposedProperties() as $property) {
            $this->$property = $defaultProperties[$property] ?? null;
        }

        foreach ($inspectedRenderable->getExposedGetters() as $getter) {
            $setter = substr_replace($getter, 'set', 0, 3);

            if (method_exists($this, $setter)) {
                $this->$setter($defaultProperties[lcfirst(substr($getter, 3))] ?? null);
            }
        }
    }
}
