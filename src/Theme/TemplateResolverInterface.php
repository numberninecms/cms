<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Theme;

use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\Term;
use NumberNine\Model\Content\ContentType;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\ShortcodeInterface;
use Twig\TemplateWrapper;

interface TemplateResolverInterface
{
    public function resolveSingle(ContentEntity $entity, array $extraTemplates = []): TemplateWrapper;

    public function resolveIndex(string $customType, array $extraTemplates = []): string;

    public function resolveTermIndex(Term $term, array $extraTemplates = []): string;

    public function resolveShortcode(AbstractShortcode $shortcode): string;

    public function resolveShortcodePageBuilder(AbstractShortcode $shortcode): TemplateWrapper;

    public function resolveBaseLayout(): string;

    public function resolvePath(string $path): TemplateWrapper;

    public function getShortcodeTemplatesCandidates(ShortcodeInterface $shortcode, string $type): array;

    public function getContentEntitySingleTemplateCandidates(ContentType $type): array;
}
