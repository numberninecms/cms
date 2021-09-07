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
use NumberNine\Twig\Extension\ShortcodeRuntime;

/**
 * @internal
 * @coversNothing
 */
final class ShortcodeRuntimeTest extends DotEnvAwareWebTestCase
{
    private ShortcodeRuntime $runtime;

    protected function setUp(): void
    {
        parent::setUp();
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
