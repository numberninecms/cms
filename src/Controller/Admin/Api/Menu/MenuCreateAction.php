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

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Entity\Menu;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Http\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/menus/", name="numbernine_admin_menus_post_item", options={"expose"=true}, methods={"POST"})
 */
final class MenuCreateAction implements AdminController
{
    public function __invoke(
        Request $request,
        ResponseFactory $responseFactory,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $name = $request->request->get('menu');

        if (!$name) {
            throw new BadRequestHttpException('Menu name not specified.');
        }

        $menu = (new Menu())->setName($name);
        $entityManager->persist($menu);
        $entityManager->flush();

        return $responseFactory->createSerializedJsonResponse($menu);
    }
}
