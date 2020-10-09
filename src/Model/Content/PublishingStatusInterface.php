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

interface PublishingStatusInterface
{
    public const STATUS_PUBLISH = 'publish';
    public const STATUS_PENDING_REVIEW = 'pending_review';
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PRIVATE = 'private';
    public const STATUS_PASSWORD = 'password';
}
