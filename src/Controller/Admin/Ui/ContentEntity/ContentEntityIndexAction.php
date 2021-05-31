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
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\Pagination\PaginationParameters;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/{type}/", name="numbernine_admin_content_entity_index", methods={"GET"}, priority="-1000")
 */
final class ContentEntityIndexAction extends AbstractController implements AdminController
{
    public function __invoke(
        SerializerInterface $serializer,
        ContentService $contentService,
        Request $request,
        string $type
    ): Response {
        $contentType = $contentService->getContentType($type);

        /** @var PaginationParameters $paginationParameters */
        $paginationParameters = $serializer->denormalize(
            $request->query->all(),
            PaginationParameters::class,
            null,
            [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]
        );

        $entities = $contentService->getEntitiesOfType($type, $paginationParameters);

        return $this->render('@NumberNine/admin/content_entity/index.html.twig', [
            'content_type' => $contentType,
            'type_slug' => $type,
            'entities' => $entities,
        ]);
    }
}
