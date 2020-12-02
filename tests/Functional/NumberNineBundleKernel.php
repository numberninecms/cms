<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\Functional;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use NumberNine\Bundle\NumberNineBundle;
use Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\WebpackEncoreBundle\WebpackEncoreBundle;

class NumberNineBundleKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new SecurityBundle(),
            new TwigBundle(),
            new CmfRoutingBundle(),
            new StofDoctrineExtensionsBundle(),
            new WebpackEncoreBundle(),
            new DoctrineBundle(),
            new NumberNineBundle(),
        ];
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->extension('framework', [
            'secret' => 'F00',
            'test' => true,
        ]);

        $container->extension('doctrine', [
            'dbal' => [
                'url' => 'mysql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=5.7',
            ],
        ]);

        $container->import('../../src/Bundle/Resources/config/app.yaml');
        $container->import('../../src/Bundle/Resources/config/services.yaml');
        $container->import('../../src/Bundle/Resources/config/{services}_' . $this->environment . '.yaml');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('../../src/Bundle/Resources/config/routing.yaml');
    }

    public function getCacheDir(): string
    {
        return __DIR__ . '/../../var/cache/' . spl_object_hash($this);
    }
}
