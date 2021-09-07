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

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use NumberNine\Http\ResponseFactory;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Repository\TermRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'terms/{taxonomy}/delete-collection', name: 'numbernine_admin_terms_delete_collection', options: ['expose' => true], methods: [
    'POST',
])]
final class TermDeleteAction implements AdminController
{
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        TermRepository $termRepository,
        ResponseFactory $responseFactory,
        string $taxonomy
    ): JsonResponse {
        /** @var array $ids */
        $ids = $request->request->get('ids');

        try {
            $termRepository->removeCollection($ids);
            $entityManager->flush();
        } catch (Exception $e) {
            return $responseFactory->createErrorJsonResponse($e->getMessage());
        }

        return $responseFactory->createSuccessJsonResponse();
    }
}
