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

use NumberNine\Http\ResponseFactory;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Repository\UserRoleRepository;
use NumberNine\Security\Capabilities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/users/roles/', name: 'numbernine_admin_user_roles_get_collection', options: ['expose' => true], methods: [
    'GET',
], priority: 1000)]
final class UserRolesGetAction extends AbstractController implements AdminController
{
    public function __invoke(ResponseFactory $responseFactory, UserRoleRepository $userRoleRepository): JsonResponse
    {
        $this->denyAccessUnlessGranted(Capabilities::LIST_USERS);

        $roles = $userRoleRepository->findAll();

        return $responseFactory->createSerializedJsonResponse($roles, ['groups' => ['role_get']]);
    }
}
