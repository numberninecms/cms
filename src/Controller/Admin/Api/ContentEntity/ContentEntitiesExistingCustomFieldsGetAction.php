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
use NumberNine\Model\Admin\AdminController;
use NumberNine\Repository\ContentEntityRepository;
use NumberNine\Security\Capabilities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'content_entities/existing-custom-fields/', name: 'numbernine_admin_contententity_customfields_get_collection', options: ['expose' => true], methods: [
    'GET',
])]
final class ContentEntitiesExistingCustomFieldsGetAction extends AbstractController implements AdminController
{
    public function __invoke(
        ResponseFactory $responseFactory,
        ContentService $contentService,
        ContentEntityRepository $contentEntityRepository
    ): JsonResponse {
        $contentType = $contentService->getContentType('page');

        $this->denyAccessUnlessGranted($contentType->getMappedCapability(Capabilities::EDIT_POSTS));

        $existingCustomFieldsNames = array_map(
            static function ($row): string {
                return $row;
            },
            $contentEntityRepository->findExistingCustomFieldsNames()
        );

        return $responseFactory->createSerializedJsonResponse($existingCustomFieldsNames);
    }
}
