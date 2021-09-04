<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Exception;

use LogicException;

final class ContentTypeNotFoundException extends LogicException
{
    /**
     * ContentTypeNotFoundException constructor.
     */
    public function __construct(string $contentType)
    {
        parent::__construct(sprintf('Content type "%s" hasn\'t been registered.', $contentType));
    }
}
