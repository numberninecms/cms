<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Ui\ContentEntity;

use NumberNine\Content\ContentService;
use NumberNine\Repository\ContentEntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[\Symfony\Component\Routing\Annotation\Route(path: '/{type}/{id<\d+>}/', name: 'numbernine_admin_content_entity_edit', methods: ['GET', 'POST'], priority: '-1000')]
final class ContentEntityEditAction extends AbstractContentEntityFormAction
{
    public function __invoke(
        Request $request,
        ContentService $contentService,
        ContentEntityRepository $contentEntityRepository,
        int $id,
        string $type
    ): Response {
        $contentType = $contentService->getContentType($type);
        $entity = $contentEntityRepository->findOneForEdition($id);

        return $this->handle($request, $contentType, $entity);
    }
}
