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

use NumberNine\Twig\Extension\ShortcodeRuntime;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @coversNothing
 */
final class ShortcodeRuntimeTest extends WebTestCase
{
    private KernelBrowser $client;
    private ShortcodeRuntime $runtime;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->request('GET', '/');
        $this->runtime = static::getContainer()->get(ShortcodeRuntime::class);
    }

    public function testRenderTextShortcode(): void
    {
        static::assertSame("Test\n", $this->runtime->renderShortcode('[text]Test[/text]'));
    }

    public function testRenderUnknownShortcode(): void
    {
        static::assertSame('[nonexistent]', $this->runtime->renderShortcode('[nonexistent]'));
    }
}
