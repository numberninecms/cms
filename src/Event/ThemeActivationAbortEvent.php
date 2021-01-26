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

final class ThemeActivationAbortEvent extends Event
{
    private bool $abort;

    public function __construct(bool $abort)
    {
        $this->abort = $abort;
    }

    public function getAbort(): bool
    {
        return $this->abort;
    }

    public function setAbort(bool $abort): void
    {
        $this->abort = $abort;
    }
}
