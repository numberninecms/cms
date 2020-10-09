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

use Doctrine\ORM\ORMException;
use NumberNine\Entity\ContentEntity;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Security\Capabilities;
use NumberNine\Content\ContentService;
use NumberNine\Http\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Inflector\EnglishInflector;

/**
 * @Route("content_entities/{type}/{id<\d+>}/", name="numbernine_admin_contententity_delete_item", options={"expose"=true}, methods={"DELETE"})
 */
final class ContentEntityDeleteAction extends AbstractController implements AdminController
{
    /**
     * @param ContentService $contentService
     * @param ResponseFactory $responseFactory
     * @param ContentEntity $entity
     * @return JsonResponse
     * @throws ORMException
     */
    public function __invoke(ContentService $contentService, ResponseFactory $responseFactory, ContentEntity $entity): JsonResponse
    {
        $contentType = $contentService->getContentType($entity->getType());
        $this->denyAccessUnlessGranted($contentType->getMappedCapability(Capabilities::DELETE_POSTS));

        $contentService->deleteEntitiesOfType($entity->getType(), [$entity->getId()]);

        return $responseFactory->createSuccessJsonResponse();
    }
}
