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
use NumberNine\Entity\ContentEntity;
use NumberNine\Model\Content\ContentType;

/**
 * Class InvalidContentTypeException.
 */
final class InvalidContentTypeException extends LogicException
{
    /**
     * InvalidContentTypeException constructor.
     */
    public function __construct(ContentType $contentType)
    {
        parent::__construct(sprintf(
            '%s must extend %s to qualify as content type.',
            $contentType->getEntityClassName(),
            ContentEntity::class
        ));
    }
}
