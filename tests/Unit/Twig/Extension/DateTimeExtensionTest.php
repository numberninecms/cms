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

use NumberNine\Twig\Extension\DateTimeExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @internal
 * @coversNothing
 */
final class DateTimeExtensionTest extends TestCase
{
    public function testGetFunctions(): void
    {
        $functions = (new DateTimeExtension())->getFunctions();
        static::assertContainsOnlyInstancesOf(TwigFunction::class, $functions);
    }

    public function testGetFilters(): void
    {
        $functions = (new DateTimeExtension())->getFilters();
        static::assertContainsOnlyInstancesOf(TwigFilter::class, $functions);
    }
}
