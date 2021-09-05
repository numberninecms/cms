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

use NumberNine\Entity\ContentEntity;
use NumberNine\Http\ResponseFactory;
use ReflectionException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

#[\Symfony\Component\Routing\Annotation\Route(path: 'content_entities/{type}/{id<\d+|__id__>}/', name: 'numbernine_admin_contententity_get_item', options: ['expose' => true], methods: ['GET'], priority: 100,)]
final class ContentEntityGetAction
{
    /**
     * @throws ExceptionInterface
     * @throws ReflectionException
     */
    public function __invoke(Request $request, ResponseFactory $responseFactory, ContentEntity $entity): JsonResponse
    {
        $context = [];

        if (filter_var($request->query->get('full'), FILTER_VALIDATE_BOOLEAN)) {
            $context['groups'] = [
                'content_entity_get',
                'web_access_get',
                'author_get',
                'editor_get',
                'seo_get',
                'custom_fields_get',
                'featured_image_get',
                'media_file_get',
            ];
        }

        return $responseFactory->createSerializedJsonResponse($entity, $context);
    }
}
