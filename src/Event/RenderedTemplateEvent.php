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

use NumberNine\Model\Component\RenderableInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class RenderedTemplateEvent extends Event
{
    private RenderableInterface $renderable;
    private string $template;

    public function __construct(RenderableInterface $renderable, string $template)
    {
        $this->renderable = $renderable;
        $this->template = $template;
    }

    public function getRenderable(): RenderableInterface
    {
        return $this->renderable;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }
}
