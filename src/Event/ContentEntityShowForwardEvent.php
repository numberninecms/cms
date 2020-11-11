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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

final class ContentEntityShowForwardEvent extends Event
{
    private Request $request;
    private ContentEntity $entity;
    private ?Response $response = null;

    /**
     * @param Request $request
     * @param ContentEntity $entity
     */
    public function __construct(Request $request, ContentEntity $entity)
    {
        $this->request = $request;
        $this->entity = $entity;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return ContentEntity
     */
    public function getEntity(): ContentEntity
    {
        return $this->entity;
    }

    /**
     * @return Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * @param Response|null $response
     */
    public function setResponse(?Response $response): void
    {
        $this->response = $response;
    }
}
