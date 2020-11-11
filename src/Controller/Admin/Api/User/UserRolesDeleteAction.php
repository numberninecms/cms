<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Api\User;

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Entity\UserRole;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Security\Capabilities;
use NumberNine\Http\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/users/roles/{id}/",
 *     name="numbernine_admin_user_roles_delete_item",
 *     options={"expose"=true},
 *     methods={"DELETE"}
 * )
 */
final class UserRolesDeleteAction extends AbstractController implements AdminController
{
    public function __invoke(
        EntityManagerInterface $entityManager,
        ResponseFactory $responseFactory,
        UserRole $userRole
    ): JsonResponse {
        $this->denyAccessUnlessGranted(Capabilities::MANAGE_ROLES);

        $entityManager->remove($userRole);
        $entityManager->flush();

        return $responseFactory->createSuccessJsonResponse();
    }
}
