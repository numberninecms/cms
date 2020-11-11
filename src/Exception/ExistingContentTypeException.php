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
use NumberNine\Model\Content\ContentType;

/**
 * Class ExistingContentTypeException
 * @package NumberNine\Exception
 */
final class ExistingContentTypeException extends LogicException
{
    /**
     * ExistingContentTypeException constructor.
     * @param ContentType $contentType
     */
    public function __construct(ContentType $contentType)
    {
        parent::__construct(sprintf(
            'Content type "%s" has already been registered. Choose another type name.',
            $contentType->getName()
        ));
    }
}
