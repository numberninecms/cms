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

namespace NumberNine\Model\Component\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class ComponentSupportedTemplatesEvent extends Event
{
    /**
     * @param string[] $supportedTemplates
     */
    public function __construct(private string $componentName, private array $supportedTemplates = [])
    {
    }

    public function getComponentName(): string
    {
        return $this->componentName;
    }

    public function getSupportedTemplates(): array
    {
        return $this->supportedTemplates;
    }

    public function setSupportedTemplates(array $supportedTemplates): self
    {
        $this->supportedTemplates = $supportedTemplates;

        return $this;
    }

    public function addSupportedTemplate(string $template): self
    {
        $this->supportedTemplates[] = $template;

        return $this;
    }
}
