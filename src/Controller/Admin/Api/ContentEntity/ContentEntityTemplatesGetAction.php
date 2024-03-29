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
use NumberNine\Theme\TemplateResolverInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'content_entities/{type}/templates/', name: 'numbernine_admin_contententity_templates_get_collection', options: ['expose' => true], methods: [
    'GET',
], priority: 200, )]
final class ContentEntityTemplatesGetAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        ResponseFactory $responseFactory,
        ContentService $contentService,
        TemplateResolverInterface $templateResolver,
        string $type
    ): JsonResponse {
        $contentType = $contentService->getContentType($type);
        $candidates = array_merge(
            $templateResolver->getContentEntitySingleTemplateCandidates($contentType),
            $templateResolver->getContentEntityIndexTemplateCandidates(),
        );

        return $responseFactory->createSerializedJsonResponse(array_values($candidates));
    }
}
