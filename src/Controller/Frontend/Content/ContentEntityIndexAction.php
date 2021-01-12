<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Frontend\Content;

use Doctrine\Common\Collections\Criteria;
use NumberNine\Model\General\Settings;
use NumberNine\Model\Pagination\PaginationParameters;
use NumberNine\Content\ContentService;
use NumberNine\Configuration\ConfigurationReadWriter;
use NumberNine\Theme\TemplateResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class ContentEntityIndexAction extends AbstractController
{
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        ContentService $contentService,
        ConfigurationReadWriter $configurationReadWriter,
        TemplateResolver $templateResolver
    ): Response {
        $page = $request->get('page', 1);

        $perPage = $configurationReadWriter->read(Settings::POSTS_PER_PAGE, 12);

        /** @var PaginationParameters $paginationParameters */
        $paginationParameters = $serializer->denormalize(
            $request->query->all(),
            PaginationParameters::class,
            null,
            [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]
        );

        $paginationParameters
            ->setStartRow($perPage * ($page - 1))
            ->setFetchCount($perPage)
            ->setOrderBy('createdAt')
            ->setOrder('DESC');

        $criteria = (new Criteria())
            ->andWhere((Criteria::expr())->eq('c.status', 'publish'));

        $contentEntities = $contentService->getEntitiesOfType(
            $contentService->getContentType('post'),
            $paginationParameters,
            $criteria
        );

        return $this->render(
            $templateResolver->resolveIndex('post'),
            [
                'entities' => $contentEntities,
            ]
        );
    }
}
