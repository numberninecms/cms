<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Content;

interface CommentStatusInterface
{
    public const COMMENT_STATUS_OPEN = 'open';
    public const COMMENT_STATUS_CLOSED = 'closed';

    public const COMMENT_STATUS_PENDING = 'pending';
    public const COMMENT_STATUS_APPROVED = 'approved';
    public const COMMENT_STATUS_SPAM = 'spam';
}
