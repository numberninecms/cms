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

use Doctrine\ORM\Query\QueryException;
use NumberNine\Model\Pagination\PaginationParameters;
use NumberNine\Content\ContentService;
use NumberNine\Http\ResponseFactory;
use ReflectionException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     "content_entities/{type}",
 *     name="numbernine_admin_contententity_get_collection",
 *     options={"expose"=true},
 *     methods={"GET"}
 * )
 */
final class ContentEntitiesGetAction
{
    /**
     * @param Request $request
     * @param Serializer $serializer
     * @param ContentService $contentService
     * @param ResponseFactory $responseFactory
     * @param string $type
     * @return JsonResponse
     * @throws ReflectionException
     * @throws ExceptionInterface
     * @throws QueryException
     */
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        ContentService $contentService,
        ResponseFactory $responseFactory,
        string $type
    ): JsonResponse {
        /** @var PaginationParameters $paginationParameters */
        $paginationParameters = $serializer->denormalize(
            $request->query->all(),
            PaginationParameters::class,
            null,
            [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]
        );

        $entities = $contentService->getEntitiesOfType($type, $paginationParameters);

        return $responseFactory->createSerializedPaginatedJsonResponse($entities);
    }
}
