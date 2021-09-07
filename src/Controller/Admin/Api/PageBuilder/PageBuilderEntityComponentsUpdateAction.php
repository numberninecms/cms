<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Api\PageBuilder;

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Content\ArrayToShortcodeConverter;
use NumberNine\Entity\ContentEntity;
use NumberNine\Http\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'page_builder/{id<\d+>}/components', name: 'numbernine_admin_pagebuilder_post_entity_components', options: ['expose' => true], methods: [
    'POST',
])]
final class PageBuilderEntityComponentsUpdateAction
{
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        ResponseFactory $responseFactory,
        ArrayToShortcodeConverter $arrayToShortcodeConverter,
        ContentEntity $contentEntity
    ): JsonResponse {
        /** @var array $components */
        $components = $request->request->get('components');
        $text = $arrayToShortcodeConverter->convertMany($components);

        $contentEntity->setContent($text);
        $entityManager->flush();

        return $responseFactory->createSuccessJsonResponse();
    }
}
