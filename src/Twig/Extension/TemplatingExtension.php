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

namespace NumberNine\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use function NumberNine\Common\Util\ArrayUtil\array_implode_associative;

final class TemplatingExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('N9_create_attributes', [$this, 'createAttributes'], ['is_safe' => ['html' => true]]),
        ];
    }

    public function createAttributes(array $attributes): string
    {
        return array_implode_associative($attributes, ' ', '=', '', '"');
    }
}
