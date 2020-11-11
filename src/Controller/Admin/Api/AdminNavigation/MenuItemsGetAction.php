<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Api\AdminNavigation;

use NumberNine\Model\Admin\AdminController;
use NumberNine\Security\Capabilities;
use NumberNine\Http\ResponseFactory;
use NumberNine\Admin\AdminMenuBuilderStore;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("menu_items", name="numbernine_admin_menuitems_get_collection", options={"expose"=true}, methods={"GET"})
 */
final class MenuItemsGetAction extends AbstractController implements AdminController
{
    public function __invoke(
        ResponseFactory $responseFactory,
        AdminMenuBuilderStore $adminMenuBuilderStore
    ): JsonResponse {
        $this->denyAccessUnlessGranted(Capabilities::EDIT_POSTS);

        return $responseFactory->createSerializedJsonResponse(
            $adminMenuBuilderStore->getAdminMenuBuilder()->getMenuItems()
        );
    }
}
