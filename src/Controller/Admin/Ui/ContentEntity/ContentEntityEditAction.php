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
use NumberNine\Entity\ContentEntity;
use NumberNine\Model\Admin\AdminController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/{type}/{id<\d+>}/", name="numbernine_admin_content_entity_edit", methods={"GET", "POST"})
 */
final class ContentEntityEditAction extends AbstractController implements AdminController
{
    public function __invoke(
        SerializerInterface $serializer,
        ContentService $contentService,
        Request $request,
        ContentEntity $entity,
        string $type
    ): Response {
        $contentType = $contentService->getContentType($type);

        return $this->render('@NumberNine/admin/content_entity/edit.html.twig', [
            'content_type' => $contentType,
            'type_slug' => $type,
            'entity' => $entity,
        ]);
    }
}
