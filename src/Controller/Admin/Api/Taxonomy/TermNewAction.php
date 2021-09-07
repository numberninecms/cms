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

use NumberNine\Entity\Term;
use NumberNine\Http\ResponseFactory;
use NumberNine\Repository\TaxonomyRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'terms/{taxonomy}/', name: 'numbernine_admin_terms_new_item', options: ['expose' => true], methods: [
    'POST',
])]
final class TermNewAction extends AbstractTermController
{
    public function __invoke(
        TaxonomyRepository $taxonomyRepository,
        ResponseFactory $responseFactory,
        string $taxonomy
    ): JsonResponse {
        $taxonomyEntity = $taxonomyRepository->findOneBy(['name' => $taxonomy]);

        if (!$taxonomyEntity) {
            return $responseFactory->createErrorJsonResponse('Taxonomy not found.');
        }

        $term = (new Term())->setTaxonomy($taxonomyEntity);

        return $this->handle($term);
    }
}
