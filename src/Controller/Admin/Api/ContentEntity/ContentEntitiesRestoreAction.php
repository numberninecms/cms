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

use Exception;
use NumberNine\Entity\MediaFile;
use NumberNine\Content\ContentService;
use NumberNine\Http\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[\Symfony\Component\Routing\Annotation\Route(path: 'content_entities/{type}/restore-collection', name: 'numbernine_admin_contententity_restore_collection', options: ['expose' => true], methods: ['POST'])]
final class ContentEntitiesRestoreAction
{
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        ContentService $contentService,
        ResponseFactory $responseFactory,
        string $type
    ): JsonResponse {
        /** @var array $ids */
        $ids = $request->request->get('ids');

        try {
            $contentService->restoreEntitiesOfType($type, $ids);
        } catch (Exception $e) {
            return $responseFactory->createErrorJsonResponse($e->getMessage());
        }

        return $responseFactory->createSuccessJsonResponse();
    }
}
