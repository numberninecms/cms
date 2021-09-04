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

namespace NumberNine\Twig\Extension;

use NumberNine\Admin\AdminMenuBuilderStore;
use NumberNine\Content\ContentService;
use NumberNine\Content\PermalinkGenerator;
use NumberNine\Entity\ContentEntity;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Extension\RuntimeExtensionInterface;

final class AdminRuntime implements RuntimeExtensionInterface
{
    public function __construct(private AdminMenuBuilderStore $adminMenuBuilderStore, private UrlGeneratorInterface $urlGenerator, private PermalinkGenerator $permalinkGenerator, private ContentService $contentService, private SluggerInterface $slugger)
    {
    }

    public function getAdminMenuItems(): array
    {
        return $this->adminMenuBuilderStore->getAdminMenuBuilder()->getMenuItems();
    }

    public function getHighlightedPermalinkUrl(ContentEntity $entity): string
    {
        $contentType = $this->contentService->getContentType((string)$entity->getCustomType());
        $defaultSlug = $this->slugger->slug((string)$contentType->getLabels()->getNewItem())->lower()->toString();
        $slug = $entity->getSlug() ?: $defaultSlug;

        $relativeUrl = $this->permalinkGenerator->generateContentEntityPermalink($entity);
        $highlightedRelativeUrl = str_replace(
            $slug,
            sprintf('<span class="slug">%s</span>', $slug),
            $relativeUrl,
        );

        return sprintf(
            '%s%s',
            $this->urlGenerator->generate('numbernine_homepage', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ltrim($highlightedRelativeUrl, '/'),
        );
    }
}
