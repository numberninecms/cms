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

use NumberNine\Exception\InvalidTimestampException;
use NumberNine\Tests\DotEnvAwareWebTestCase;
use NumberNine\Twig\Extension\DateTimeRuntime;

/**
 * @internal
 * @coversNothing
 */
final class DateTimeRuntimeTest extends DotEnvAwareWebTestCase
{
    private DateTimeRuntime $runtime;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client->request('GET', '/');
        $this->runtime = static::getContainer()->get(DateTimeRuntime::class);
    }

    public function testGetTimeLeftInWordsWithInvalidTimestampThrowsException(): void
    {
        $this->expectException(InvalidTimestampException::class);
        $this->runtime->getTimeLeftInWords('invalid');
    }

    public function testGetTimeLeftInWordsWithTimestampWorks(): void
    {
        $date = new \DateTime();
        $date->modify('+4 day');

        static::assertSame('4 days left', $this->runtime->getTimeLeftInWords($date->getTimestamp()));
    }

    public function testGetTimeLeftInWordsWithDateTimeWorks(): void
    {
        $date = new \DateTime();
        $date->modify('+30 minutes');

        static::assertSame('30 minutes left', $this->runtime->getTimeLeftInWords($date));
    }

    public function testGetTimeAgoInWordsWithInvalidTimestampThrowsException(): void
    {
        $this->expectException(InvalidTimestampException::class);
        $this->runtime->getTimeAgoInWords('invalid');
    }

    public function testGetTimeAgoInWordsWithTimestampWorks(): void
    {
        $date = new \DateTime();
        $date->modify('-4 day');

        static::assertSame('4 days ago', $this->runtime->getTimeAgoInWords($date->getTimestamp()));
    }

    public function testGetTimeAgoInWordsWithDateTimeWorks(): void
    {
        $date = new \DateTime();
        $date->modify('-30 minutes');

        static::assertSame('30 minutes ago', $this->runtime->getTimeAgoInWords($date));
    }
}
