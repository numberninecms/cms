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
use NumberNine\Content\ContentService;
use NumberNine\Http\ResponseFactory;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Security\Capabilities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: 'content_entities/{type}/delete-collection', name: 'numbernine_admin_contententity_delete_collection', options: ['expose' => true], methods: [
    'POST',
])]
final class ContentEntitiesDeleteAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        ContentService $contentService,
        ResponseFactory $responseFactory,
        string $type
    ): JsonResponse {
        $contentType = $contentService->getContentType($type);

        $this->denyAccessUnlessGranted($contentType->getMappedCapability(Capabilities::DELETE_POSTS));

        $ids = $request->request->all('ids');

        try {
            if (empty($ids)) {
                $contentService->deletePermanentlyAllEntitiesOfType($type);
            } else {
                $contentService->deleteEntitiesOfType($type, $ids);
            }
        } catch (Exception $e) {
            return $responseFactory->createErrorJsonResponse($e->getMessage());
        }

        return $responseFactory->createSuccessJsonResponse();
    }
}
