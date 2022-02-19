<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\Tests\Unit\Twig\Extension;

use NumberNine\Twig\Extension\AssetRuntime;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\WebpackEncoreBundle\Exception\EntrypointNotFoundException;

/**
 * @internal
 * @coversNothing
 */
final class AssetRuntimeTest extends WebTestCase
{
    private KernelBrowser $client;
    private AssetRuntime $runtime;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->request('GET', '/');
        $this->runtime = static::getContainer()->get(AssetRuntime::class);
    }

    public function testRenderStylesheetTagInvalidEntrypoint(): void
    {
        $this->expectException(EntrypointNotFoundException::class);
        $this->runtime->renderStylesheetTag('invalid');
    }
}
