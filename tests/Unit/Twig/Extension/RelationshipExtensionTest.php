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

use NumberNine\Twig\Extension\RelationshipExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

final class RelationshipExtensionTest extends TestCase
{
    public function testGetFunctions(): void
    {
        $functions = (new RelationshipExtension())->getFunctions();
        $this->assertContainsOnlyInstancesOf(TwigFunction::class, $functions);
    }
}
