<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Api\Menu;

use Doctrine\ORM\Tools\Pagination\Paginator;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Repository\ContentEntityRepository;
use NumberNine\Content\ContentService;
use NumberNine\Http\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[\Symfony\Component\Routing\Annotation\Route(path: '/menus/entities/{type}/{page<\d+>}/', name: 'numbernine_admin_menus_get_entities', options: ['expose' => true], methods: ['GET'])]
final class MenuContentEntitiesGetAction implements AdminController
{
    public function __invoke(
        ResponseFactory $responseFactory,
        ContentService $contentService,
        ContentEntityRepository $contentEntityRepository,
        string $type,
        int $page
    ): JsonResponse {
        $contentEntities = $contentEntityRepository->getSimplePaginatedCollectionQueryBuilder(
            $type,
            ($page - 1) * 20,
            20
        );

        return $responseFactory->createSerializedPaginatedJsonResponse(
            new Paginator($contentEntities),
            ['groups' => ['menu_get']]
        );
    }
}
