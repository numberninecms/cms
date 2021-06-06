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

use NumberNine\Entity\ContentEntity;

final class InvalidFeaturedImageEntityException extends \LogicException
{
    public function __construct(ContentEntity $contentEntity)
    {
        parent::__construct(sprintf(
            'ContentEntity with ID "%d" is not a MediaFile entity and should not be linked as a featured image.',
            $contentEntity->getId()
        ));
    }
}
