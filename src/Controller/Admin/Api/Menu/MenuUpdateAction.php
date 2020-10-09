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
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/menus/{id}/", name="numbernine_admin_menus_set_menu_items", options={"expose"=true}, methods={"POST"})
 */
final class MenuUpdateAction implements AdminController
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager, ResponseFactory $responseFactory, Menu $menu): JsonResponse
    {
        /** @var array $menuItems */
        $menuItems = $request->request->get('menuItems') ?: [];

        $menu->setMenuItems($menuItems);

        $entityManager->flush();

        return $responseFactory->createSuccessJsonResponse();
    }
}
