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

use NumberNine\Entity\Post;
use NumberNine\Exception\ThemeEventNotFoundException;
use NumberNine\Twig\Extension\EventRuntime;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @coversNothing
 */
final class EventRuntimeTest extends WebTestCase
{
    private KernelBrowser $client;
    private EventRuntime $runtime;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->request('GET', '/');
        $this->runtime = static::getContainer()->get(EventRuntime::class);
    }

    public function testHead(): void
    {
        static::assertStringContainsString('<title>', $this->runtime->head());
    }

    public function testFooter(): void
    {
        static::assertIsString($this->runtime->footer());
    }

    public function testDispatch(): void
    {
        $this->expectException(ThemeEventNotFoundException::class);
        $this->runtime->dispatch('NonExistentEvent');
    }

    public function testFilter(): void
    {
        $this->expectException(ThemeEventNotFoundException::class);
        $this->runtime->filter(new Post(), 'NonExistentEvent');
    }
}
