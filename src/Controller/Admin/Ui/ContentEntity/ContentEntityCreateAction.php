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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[\Symfony\Component\Routing\Annotation\Route(path: '/{type}/new/', name: 'numbernine_admin_content_entity_create', methods: ['GET', 'POST'], priority: '-1000')]
final class ContentEntityCreateAction extends AbstractContentEntityFormAction
{
    public function __invoke(
        ContentService $contentService,
        Request $request,
        string $type
    ): Response {
        $contentType = $contentService->getContentType($type);

        $class = $contentType->getEntityClassName();
        $entity = (new $class())
            ->setAuthor($this->getUser())
            ->setCustomType($contentType->getName())
            ->setCustomFields(null)
        ;

        return $this->handle($request, $contentType, $entity);
    }
}
