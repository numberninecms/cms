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

use NumberNine\Entity\ContentEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'page_builder/{id<\d+>}/components', name: 'numbernine_admin_pagebuilder_entity_get_components', options: ['expose' => true], methods: [
    'GET',
])]
final class PageBuilderEntityComponentsGetAction extends AbstractPageBuilderGetAction
{
    public function __invoke(ContentEntity $contentEntity): JsonResponse
    {
        return $this->createPageBuilderResponseFromText((string) $contentEntity->getContent());
    }
}
