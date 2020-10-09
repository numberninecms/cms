<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Api\Taxonomy;

use Doctrine\ORM\Tools\Pagination\Paginator;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\Pagination\PaginationParameters;
use NumberNine\Repository\TermRepository;
use NumberNine\Http\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("terms/{taxonomy}/", name="numbernine_admin_terms_get_collection", options={"expose": true}, methods={"GET"})
 */
final class TermsGetAction implements AdminController
{
    public function __invoke(Request $request, SerializerInterface $serializer, TermRepository $termRepository, ResponseFactory $responseFactory, string $taxonomy): JsonResponse
    {
        /** @var PaginationParameters $paginationParameters */
        $paginationParameters = $serializer->denormalize($request->query->all(), PaginationParameters::class, null, [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]);

        $queryBuilder = $termRepository->getByTaxonomyPaginatedCollectionQueryBuilder($taxonomy, $paginationParameters);
        $terms = new Paginator($queryBuilder, true);

        return $responseFactory->createSerializedPaginatedJsonResponse($terms);
    }
}
