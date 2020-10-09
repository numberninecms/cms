<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Component\Features;

use NumberNine\Theme\TemplateResolver;

trait TemplateResolverTrait
{
    protected TemplateResolver $templateResolver;

    /**
     * @param TemplateResolver $templateResolver
     */
    final public function setTemplateResolver(TemplateResolver $templateResolver): void
    {
        $this->templateResolver = $templateResolver;
    }

    protected function resolveTemplate(): ?string
    {
        return null;
    }
}
