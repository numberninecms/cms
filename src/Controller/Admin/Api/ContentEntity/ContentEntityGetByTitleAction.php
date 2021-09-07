<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Api\ContentEntity;

use NumberNine\Content\ContentService;
use NumberNine\Http\ResponseFactory;
use ReflectionException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'content_entities/{type}/title/{title}/', name: 'numbernine_admin_contententity_get_item_by_title', options: ['expose' => true], methods: [
    'GET',
], priority: 0, )]
final class ContentEntityGetByTitleAction
{
    /**
     * @throws ReflectionException
     */
    public function __invoke(
        Request $request,
        ContentService $contentService,
        ResponseFactory $responseFactory,
        string $type,
        string $title
    ): JsonResponse {
        $entity = $contentService->getEntityOfTypeBy($type, ['title' => $title]);

        return $responseFactory->createSerializedJsonResponse($entity);
    }
}
