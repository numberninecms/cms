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

use NumberNine\Model\Admin\AdminController;
use NumberNine\Repository\TaxonomyRepository;
use NumberNine\Http\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("taxonomies/", name="numbernine_admin_taxonomies_get_collection", options={"expose": true}, methods={"GET"})
 */
final class TaxonomiesGetAction implements AdminController
{
    public function __invoke(TaxonomyRepository $taxonomyRepository, ResponseFactory $responseFactory): JsonResponse
    {
        $taxonomies = $taxonomyRepository->findAll();

        return $responseFactory->createSerializedJsonResponse($taxonomies, ['groups' => ['taxonomy_get']]);
    }
}
