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

namespace NumberNine\Exception;

use LogicException;

final class ThemeEventNotFoundException extends LogicException
{
    public function __construct(string $eventName)
    {
        parent::__construct(sprintf('Theme event "%s" not found.', $eventName));
    }
}
