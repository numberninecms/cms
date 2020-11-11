<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Api\Menu;

use NumberNine\Model\Admin\AdminController;
use NumberNine\Repository\MenuRepository;
use NumberNine\Http\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/menus/", name="numbernine_admin_menus_get_collection", options={"expose"=true}, methods={"GET"})
 */
final class MenusGetAction implements AdminController
{
    public function __invoke(ResponseFactory $responseFactory, MenuRepository $menuRepository): JsonResponse
    {
        $menus = $menuRepository->findAll();

        return $responseFactory->createSerializedJsonResponse($menus);
    }
}
