<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Content;

use DateTime;
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\Term;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

final class PermalinkGenerator
{
    public function __construct(private UrlGeneratorInterface $urlGenerator, private RouteProviderInterface $routeProvider, private ContentService $contentService, private SluggerInterface $slugger)
    {
    }

    /**
     * Generates a permalink for a given ContentEntity object.
     */
    public function generateContentEntityPermalink(
        ContentEntity $contentEntity,
        int $page = 1,
        bool $absolute = false
    ): string {
        $contentType = $this->contentService->getContentType((string) $contentEntity->getCustomType());

        $routeName = sprintf('numbernine_%s_show%s', $contentEntity->getCustomType(), $page > 1 ? '_page' : '');

        $route = $this->routeProvider->getRouteByName($routeName);

        /** @var ?DateTime $date */
        $date = $contentEntity->getPublishedAt() ?? $contentEntity->getCreatedAt();

        if ($date === null) {
            $date = new DateTime();
        }

        $parameters = [];
        if (str_contains($route->getPath(), '{year}')) {
            $parameters['year'] = $date->format('Y');
        }
        if (str_contains($route->getPath(), '{month}')) {
            $parameters['month'] = $date->format('m');
        }
        if (str_contains($route->getPath(), '{day}')) {
            $parameters['day'] = $date->format('d');
        }
        if (str_contains($route->getPath(), '{slug}')) {
            $parameters['slug'] = $contentEntity->getSlug()
                ?? $this->slugger->slug((string) $contentType->getLabels()->getNewItem())->lower()->toString();
        }

        if ($page > 1) {
            $parameters['page'] = $page;
        }

        return $this->urlGenerator->generate(
            $routeName,
            $parameters,
            $absolute ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH
        );
    }

    public function generateTermPermalink(Term $term, bool $absolute = false): string
    {
        if (!($taxonomy = $term->getTaxonomy())) {
            return '';
        }

        $routeName = sprintf(
            'numbernine_taxonomy_%s_term_index',
            $this->slugger->slug((string) $taxonomy->getName(), '_')
        );
        $parameters = ['slug' => $term->getSlug()];

        return $this->urlGenerator->generate(
            $routeName,
            $parameters,
            $absolute ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH
        );
    }
}
