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

namespace NumberNine\Tests\Dummy\Twig\Loader;

use Twig\Loader\FilesystemLoader;

final class TestTemplatesTwigLoader extends FilesystemLoader
{
    public function __construct(string $projectPath)
    {
        parent::__construct();
        $this->addPath($projectPath . '/tests/templates/', 'NumberNineTests');
    }
}
