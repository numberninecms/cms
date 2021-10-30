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
use NumberNine\Model\Content\CommentStatusInterface;

final class UnknownCommentStatusException extends LogicException
{
    public function __construct(string $status)
    {
        parent::__construct(sprintf(
            'Status "%s" is not valid. Valid comment status are: %s, %s, %s',
            $status,
            CommentStatusInterface::COMMENT_STATUS_APPROVED,
            CommentStatusInterface::COMMENT_STATUS_PENDING,
            CommentStatusInterface::COMMENT_STATUS_SPAM,
        ));
    }
}
