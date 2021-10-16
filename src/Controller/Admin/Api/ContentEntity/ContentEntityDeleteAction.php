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
use NumberNine\Entity\ContentEntity;
use NumberNine\Http\ResponseFactory;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Security\Capabilities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: 'content_entities/{type}/{id<\d+>}/',
    name: 'numbernine_admin_contententity_delete_item',
    options: ['expose' => true],
    methods: ['DELETE'],
)]
final class ContentEntityDeleteAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        ContentService $contentService,
        ResponseFactory $responseFactory,
        ContentEntity $entity
    ): JsonResponse {
        $contentType = $contentService->getContentType($entity->getType());

        $this->denyAccessUnlessGranted($contentType->getMappedCapability(Capabilities::DELETE_POSTS));

        if ($this->getUser() !== $entity->getAuthor()) {
            $this->denyAccessUnlessGranted($contentType->getMappedCapability(Capabilities::DELETE_OTHERS_POSTS));
        }

        if ($entity->getStatus() === PublishingStatusInterface::STATUS_PUBLISH) {
            $this->denyAccessUnlessGranted($contentType->getMappedCapability(Capabilities::DELETE_PUBLISHED_POSTS));
        }

        if ($entity->getStatus() === PublishingStatusInterface::STATUS_PRIVATE) {
            $this->denyAccessUnlessGranted($contentType->getMappedCapability(Capabilities::DELETE_OTHERS_POSTS));
            $this->denyAccessUnlessGranted($contentType->getMappedCapability(Capabilities::DELETE_PRIVATE_POSTS));
        }

        $contentService->deleteEntitiesOfType($entity->getType(), [$entity->getId()]);

        return $responseFactory->createSuccessJsonResponse();
    }
}
