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

namespace NumberNine\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class MenuShortcodeStyleEvent extends Event
{
    /** @var string[] */
    private array $styles;

    public function __construct(array $styles = [])
    {
        $this->styles = $styles;
    }

    public function getStyles(): array
    {
        return $this->styles;
    }

    public function setStyles(array $styles): self
    {
        $this->styles = $styles;
        return $this;
    }

    public function addStyle(string $style, string $label): self
    {
        if (!\in_array($style, $this->styles, true)) {
            $this->styles[$label] = $style;
        }

        return $this;
    }

    public function removeStyle(string $style): self
    {
        if (($key = \array_search($style, $this->styles, true)) !== false) {
            unset($this->styles[$key]);
        }

        return $this;
    }
}
