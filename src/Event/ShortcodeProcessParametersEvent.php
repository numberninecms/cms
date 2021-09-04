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

use NumberNine\Model\Shortcode\ShortcodeInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class ShortcodeProcessParametersEvent extends Event
{
    private array $parameters;

    public function __construct(private ShortcodeInterface $shortcode, array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function getShortcode(): ShortcodeInterface
    {
        return $this->shortcode;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }
}
