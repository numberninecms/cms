<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Api\ContentType;

use NumberNine\Model\Admin\AdminController;
use NumberNine\Content\ContentService;
use NumberNine\Http\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "content-types/",
 *     name="numbernine_admin_contenttypes_get_collection",
 *     options={"expose"=true},
 *     methods={"GET"}
 * )
 */
final class ContentTypesGetAction extends AbstractController implements AdminController
{
    public function __invoke(ResponseFactory $responseFactory, ContentService $contentService): JsonResponse
    {
        return $responseFactory->createSerializedJsonResponse($contentService->getContentTypes());
    }
}
