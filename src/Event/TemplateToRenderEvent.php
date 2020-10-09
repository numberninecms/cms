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
use Symfony\Contracts\EventDispatcher\Event;
use Twig\TemplateWrapper;

final class TemplateToRenderEvent extends Event
{
    private Request $request;
    private ContentEntity $entity;
    private TemplateWrapper $template;

    public function __construct(Request $request, ContentEntity $entity, TemplateWrapper $template)
    {
        $this->request = $request;
        $this->entity = $entity;
        $this->template = $template;
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
     * @return TemplateWrapper
     */
    public function getTemplate(): TemplateWrapper
    {
        return $this->template;
    }

    /**
     * @param TemplateWrapper $template
     */
    public function setTemplate(TemplateWrapper $template): void
    {
        $this->template = $template;
    }
}
