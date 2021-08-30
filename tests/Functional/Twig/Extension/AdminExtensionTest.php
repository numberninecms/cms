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

namespace NumberNine\Tests\Functional\Twig\Extension;

use NumberNine\Twig\Extension\AdminExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

final class AdminExtensionTest extends TestCase
{
    public function testGetFunctions(): void
    {
        $functions = (new AdminExtension())->getFunctions();
        $this->assertContainsOnlyInstancesOf(TwigFunction::class, $functions);
    }
}
