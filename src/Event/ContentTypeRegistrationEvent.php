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

use NumberNine\Entity\ContentEntity;
use NumberNine\Exception\ExistingContentTypeException;
use NumberNine\Exception\InvalidContentTypeException;
use NumberNine\Model\Content\ContentType;
use Symfony\Contracts\EventDispatcher\Event;

final class ContentTypeRegistrationEvent extends Event
{
    /** @var ContentType[] */
    private array $contentTypes = [];

    /**
     * @return never
     */
    public function addContentType(ContentType $contentType): void
    {
        if (!is_subclass_of($contentType->getEntityClassName(), ContentEntity::class)) {
            throw new InvalidContentTypeException($contentType);
        }

        if (\array_key_exists($contentType->getName(), $this->contentTypes)) {
            throw new ExistingContentTypeException($contentType);
        }

        $this->contentTypes[$contentType->getName()] = $contentType;
    }

    /**
     * @return ContentType[]
     */
    public function getContentTypes(): array
    {
        return array_values($this->contentTypes);
    }
}
