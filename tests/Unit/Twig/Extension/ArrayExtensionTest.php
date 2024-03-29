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

use NumberNine\Twig\Extension\ArrayExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

/**
 * @internal
 * @coversNothing
 */
final class ArrayExtensionTest extends TestCase
{
    public function testGetFilters(): void
    {
        $functions = (new ArrayExtension())->getFilters();
        static::assertContainsOnlyInstancesOf(TwigFilter::class, $functions);
    }
}
