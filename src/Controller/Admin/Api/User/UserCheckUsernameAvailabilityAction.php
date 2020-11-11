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
use NumberNine\Model\Admin\AdminController;
use NumberNine\Repository\UserRepository;
use NumberNine\Security\Capabilities;
use NumberNine\Http\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/users/check-username-availability/{username}",
 *     name="numbernine_admin_users_check_username_availability",
 *     options={"expose"=true},
 *     methods={"GET"}
 * )
 */
final class UserCheckUsernameAvailabilityAction extends AbstractController implements AdminController
{
    public function __invoke(
        ResponseFactory $responseFactory,
        UserRepository $userRepository,
        string $username
    ): JsonResponse {
        $this->denyAccessUnlessGranted(Capabilities::EDIT_USERS);
        $this->denyAccessUnlessGranted(Capabilities::CREATE_USERS);

        $user = $userRepository->findOneBy(['username' => $username]);
        return $responseFactory->createSerializedJsonResponse(!$user instanceof User);
    }
}
