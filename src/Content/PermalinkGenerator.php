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
use NumberNine\Model\Content\PublishingStatusInterface;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

final class PermalinkGenerator
{
    private UrlGeneratorInterface $urlGenerator;
    private RouteProviderInterface $routeProvider;
    private SluggerInterface $slugger;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param RouteProviderInterface $routeProvider
     * @param SluggerInterface $slugger
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, RouteProviderInterface $routeProvider, SluggerInterface $slugger)
    {
        $this->urlGenerator = $urlGenerator;
        $this->routeProvider = $routeProvider;
        $this->slugger = $slugger;
    }

    /**
     * Generates a permalink for a given ContentEntity object
     * @param ContentEntity $contentEntity
     * @param bool $absolute
     * @return string
     */
    public function generateContentEntityPermalink(ContentEntity $contentEntity, bool $absolute = false): string
    {
        $routeName = sprintf('numbernine_%s_show', $contentEntity->getCustomType());
        $route = $this->routeProvider->getRouteByName($routeName);

        /** @var DateTime $date */
        $date = $contentEntity->getStatus() === PublishingStatusInterface::STATUS_PUBLISH
            ? $contentEntity->getPublishedAt()
            : $contentEntity->getCreatedAt();

        $parameters = [];
        if (strpos($route->getPath(), '{year}') !== false) {
            $parameters['year'] = $date->format('Y');
        }
        if (strpos($route->getPath(), '{month}') !== false) {
            $parameters['month'] = $date->format('m');
        }
        if (strpos($route->getPath(), '{day}') !== false) {
            $parameters['day'] = $date->format('d');
        }
        if (strpos($route->getPath(), '{slug}') !== false) {
            $parameters['slug'] = $contentEntity->getSlug();
        }

        return $this->urlGenerator->generate($routeName, $parameters, $absolute ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    /**
     * @param Term $term
     * @param bool $absolute
     * @return string
     */
    public function generateTermPermalink(Term $term, bool $absolute = false): string
    {
        if (!($taxonomy = $term->getTaxonomy())) {
            return '';
        }

        $routeName = sprintf('numbernine_taxonomy_%s_term_index', $this->slugger->slug((string)$taxonomy->getName(), '_'));
        $parameters = ['slug' => $term->getSlug()];

        return $this->urlGenerator->generate($routeName, $parameters, $absolute ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH);
    }
}
