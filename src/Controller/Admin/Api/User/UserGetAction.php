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

use NumberNine\Entity\User;
use NumberNine\Http\ResponseFactory;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Security\Capabilities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/users/{id<\d+>}/', name: 'numbernine_admin_users_get_item', options: ['expose' => true], methods: [
    'GET',
])]
final class UserGetAction extends AbstractController implements AdminController
{
    public function __invoke(ResponseFactory $responseFactory, User $user): JsonResponse
    {
        $this->denyAccessUnlessGranted(Capabilities::LIST_USERS);

        return $responseFactory->createSerializedJsonResponse($user, ['groups' => ['user_get']]);
    }
}
