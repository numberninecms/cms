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
use Twig\TemplateWrapper;

final class ContentEntityShowBeforeRenderEvent extends Event
{
    private ?Response $response = null;

    public function __construct(private Request $request, private ContentEntity $entity, private TemplateWrapper $template)
    {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getEntity(): ContentEntity
    {
        return $this->entity;
    }

    public function getTemplate(): TemplateWrapper
    {
        return $this->template;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(?Response $response): void
    {
        $this->response = $response;
    }
}
