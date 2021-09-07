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

use NumberNine\Tests\DotEnvAwareWebTestCase;
use NumberNine\Twig\Extension\AssetRuntime;
use Symfony\WebpackEncoreBundle\Exception\EntrypointNotFoundException;

/**
 * @internal
 * @coversNothing
 */
final class AssetRuntimeTest extends DotEnvAwareWebTestCase
{
    private AssetRuntime $runtime;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client->request('GET', '/');
        $this->runtime = static::getContainer()->get(AssetRuntime::class);
    }

    public function testRenderStylesheetTagInvalidEntrypoint(): void
    {
        $this->expectException(EntrypointNotFoundException::class);
        $this->runtime->renderStylesheetTag('invalid');
    }
}
