<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Bundle\DependencyInjection;

use NumberNine\Common\Bundle\MergeConfigurationTrait;
use NumberNine\Model\General\Settings;
use ReflectionClass;
use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

final class NumberNineExtension extends Extension implements PrependExtensionInterface
{
    use MergeConfigurationTrait;

    public function getAlias(): string
    {
        return 'numbernine';
    }

    public function prepend(ContainerBuilder $container): void
    {
        $container->loadFromExtension(
            'framework',
            [
                'translator' => [
                    'paths' => [
                        __DIR__ . '/../Resources/translations',
                    ]
                ]
            ]
        );

        $reflection = new ReflectionClass(Settings::class);
        $constants = $reflection->getConstants();

        $container->loadFromExtension(
            'twig',
            [
                'globals' => $constants,
            ]
        );

        // original security config
        $securityConfig = null;
        if ($container->hasExtension('security')) {
            $securityConfig = $container->getExtensionConfig('security');
        }

        $securityModified = false;
        $securityConfigs = [];

        $extensions = $container->getExtensions();

        $resource = Yaml::parseFile(__DIR__ . '/../Resources/config/app.yaml');
        foreach ($resource as $name => $config) {
            if (empty($extensions[$name])) {
                continue;
            }
            if ($name === 'security') {
                $securityConfigs[] = $config;
                $securityModified = true;
            } else {
                $container->prependExtensionConfig($name, $config);
            }
        }

        if ($securityModified) {
            $securityConfigs = array_reverse($securityConfigs);
            foreach ($securityConfigs as $config) {
                $this->mergeConfigIntoOne($container, 'security', $config);
            }
        }
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $env = $container->getParameter('kernel.environment');
        try {
            $loader->load("services_$env.yaml");
        } catch (FileLocatorFileNotFoundException $e) {
            // ignore
        }
    }
}
