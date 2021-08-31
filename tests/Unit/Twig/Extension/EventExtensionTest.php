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

use NumberNine\Twig\Extension\EventExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class EventExtensionTest extends TestCase
{
    public function testGetFunctions(): void
    {
        $functions = (new EventExtension())->getFunctions();
        $this->assertContainsOnlyInstancesOf(TwigFunction::class, $functions);
    }

    public function testGetFilters(): void
    {
        $functions = (new EventExtension())->getFilters();
        $this->assertContainsOnlyInstancesOf(TwigFilter::class, $functions);
    }
}
