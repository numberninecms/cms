<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Component;

use NumberNine\Content\RenderableInspectorInterface;
use NumberNine\Theme\TemplateResolverInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;

interface RenderableInterface
{
    public function render(): string;

    public function setTemplateName(string $templateName): void;

//    public function setTwig(Environment $twig): self;
//
//    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void;
//
//    public function setRenderableInspector(RenderableInspectorInterface $renderableInspector): self;

    public function setTemplateResolver(TemplateResolverInterface $templateResolver): void;
}
