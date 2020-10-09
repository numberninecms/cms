<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Bundle;

use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use function NumberNine\Util\ArrayUtil\array_merge_recursive_distinct;

trait MergeConfigurationTrait
{
    /**
     * Merge configuration into one config
     *
     * @param ContainerBuilder $container
     * @param string $name
     * @param array $config
     *
     * @throws RuntimeException
     * @throws ReflectionException
     */
    private function mergeConfigIntoOne(ContainerBuilder $container, string $name, array $config = []): void
    {
        $originalConfig = $container->getExtensionConfig($name);
        if (!count($originalConfig)) {
            $originalConfig[] = [];
        }

        $mergedConfig = array_merge_recursive_distinct($originalConfig[0], $config);
        $originalConfig[0] = $mergedConfig;

        $this->setExtensionConfig($container, $name, $originalConfig);
    }

    /**
     * Set extension config
     *
     * Usable for extensions which requires to have just one config.
     * http://api.symfony.com/2.3/Symfony/Component/Config/Definition/Builder/ArrayNodeDefinition.html
     * #method_disallowNewKeysInSubsequentConfigs
     * @param ContainerBuilder $container
     * @param string $name
     * @param array $config
     * @throws ReflectionException
     */
    public function setExtensionConfig(ContainerBuilder $container, string $name, array $config = []): void
    {
        $classRef = new ReflectionClass(ContainerBuilder::class);
        $extensionConfigsRef = $classRef->getProperty('extensionConfigs');
        $extensionConfigsRef->setAccessible(true);

        $newConfig = $extensionConfigsRef->getValue($container);
        $newConfig[$name] = $config;
        $extensionConfigsRef->setValue($container, $newConfig);

        $extensionConfigsRef->setAccessible(false);
    }
}
