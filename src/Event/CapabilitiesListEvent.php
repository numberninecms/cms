<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class CapabilitiesListEvent extends Event
{
    /** @var string[] */
    private array $capabilities;

    public function __construct(array $capabilities)
    {
        $this->capabilities = array_values($capabilities);
    }

    public function addCapability(string $capability): void
    {
        if (!\in_array($capability, $this->capabilities, true)) {
            $this->capabilities[] = $capability;
        }
    }

    public function getCapabilities(): array
    {
        return $this->capabilities;
    }
}
