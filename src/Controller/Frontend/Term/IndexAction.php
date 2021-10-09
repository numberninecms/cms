<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Frontend\Term;

use Doctrine\Common\Collections\Criteria;
use NumberNine\Configuration\ConfigurationReadWriter;
use NumberNine\Content\ContentService;
use NumberNine\Entity\Term;
use NumberNine\Event\CurrentRequestTermEvent;
use NumberNine\EventSubscriber\RouteRegistrationEventSubscriber;
use NumberNine\Model\Content\ContentType;
use NumberNine\Model\General\Settings;
use NumberNine\Model\Pagination\PaginationParameters;
use NumberNine\Theme\TemplateResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * The route of this action is dynamically generated.
 *
 * @see RouteRegistrationEventSubscriber
 */
final class IndexAction extends AbstractController
{
    /**
     * @param Serializer $serializer
     *
     * @return Response
     */
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        ContentService $contentService,
        ConfigurationReadWriter $configurationReadWriter,
        TemplateResolver $templateResolver,
        CacheInterface $cache,
        EventDispatcherInterface $eventDispatcher,
        Term $term
    ) {
        $contentEntities = [];

        /** @var PaginationParameters $paginationParameters */
        $paginationParameters = $serializer->denormalize(
            $request->query->all(),
            PaginationParameters::class,
            null,
            [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]
        );

        $paginationParameters
            ->setFetchCount($configurationReadWriter->read(Settings::POSTS_PER_PAGE, 12))
            ->setOrderBy('createdAt')
            ->setOrder('DESC')
            ->setTerms([$term])
        ;

        $criteria = (new Criteria())
            ->andWhere((Criteria::expr())->eq('c.status', 'publish'))
        ;

        $taxonomy = $term->getTaxonomy();

        if ($taxonomy) {
            $contentTypes = array_values(
                array_filter(
                    $contentService->getContentTypes(),
                    static function (ContentType $contentType) use ($taxonomy): bool {
                        return \in_array($contentType->getName(), $taxonomy->getContentTypes() ?? [], true);
                    }
                )
            );

            $contentEntities = $contentService->getEntitiesOfMultipleTypes(
                $contentTypes,
                $paginationParameters,
                $criteria
            );
        }

        $eventDispatcher->dispatch(new CurrentRequestTermEvent($term));

        return $this->render($templateResolver->resolveTermIndex($term), ['entities' => $contentEntities]);
    }
}
