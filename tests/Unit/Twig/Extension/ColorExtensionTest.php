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

use NumberNine\Twig\Extension\ColorExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * @internal
 * @coversNothing
 */
final class ColorExtensionTest extends TestCase
{
    public function testGetFunctions(): void
    {
        $functions = (new ColorExtension())->getFunctions();
        static::assertContainsOnlyInstancesOf(TwigFunction::class, $functions);
    }
}
