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
use NumberNine\Model\Admin\AdminController;
use NumberNine\Http\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("terms/{taxonomy}/{id}/", name="numbernine_admin_terms_get_item", options={"expose": true}, methods={"GET"})
 */
final class TermGetAction extends AbstractController implements AdminController
{
    public function __invoke(ResponseFactory $responseFactory, Term $term): JsonResponse
    {
        return $responseFactory->createSerializedJsonResponse($term, ['groups' => ['term_get']]);
    }
}
