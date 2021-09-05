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

#[\Symfony\Component\Routing\Annotation\Route(path: '/users/check-email-availability/{email}', name: 'numbernine_admin_users_check_email_availability', options: ['expose' => true], methods: ['GET'])]
final class UserCheckEmailAvailabilityAction extends AbstractController implements AdminController
{
    public function __invoke(
        ResponseFactory $responseFactory,
        UserRepository $userRepository,
        string $email
    ): JsonResponse {
        $this->denyAccessUnlessGranted(Capabilities::EDIT_USERS);
        $this->denyAccessUnlessGranted(Capabilities::CREATE_USERS);

        $user = $userRepository->findOneBy(['email' => $email]);
        return $responseFactory->createSerializedJsonResponse(!$user instanceof User);
    }
}
