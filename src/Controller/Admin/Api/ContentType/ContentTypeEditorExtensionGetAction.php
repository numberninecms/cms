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
use NumberNine\Model\Content\EditorExtensionBuilder;
use NumberNine\Model\Content\EditorExtensionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[\Symfony\Component\Routing\Annotation\Route(path: 'content-types/{type}/editor-extension/', name: 'numbernine_admin_contenttype_editor_extension_get_item', options: ['expose' => true], methods: ['GET'])]
final class ContentTypeEditorExtensionGetAction extends AbstractController implements AdminController
{
    public function __invoke(
        ResponseFactory $responseFactory,
        ContentService $contentService,
        string $type
    ): JsonResponse {
        $contentType = $contentService->getContentType($type);
        $extensionOutput = [];

        if ($extensionClassName = $contentType->getEditorExtension()) {
            /** @var EditorExtensionInterface $extension */
            $extension = new $extensionClassName();
            $builder = new EditorExtensionBuilder();
            $extension->build($builder);
            $extensionOutput = $builder->all();
        }

        return $responseFactory->createSerializedJsonResponse($extensionOutput);
    }
}
