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
use NumberNine\Entity\User;
use NumberNine\Entity\UserRole;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Repository\UserRoleRepository;
use NumberNine\Security\Capabilities;
use NumberNine\Http\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserCreateUpdateAction extends AbstractController implements AdminController
{
    public function __construct(private EntityManagerInterface $entityManager, private UserRoleRepository $userRoleRepository, private ResponseFactory $responseFactory)
    {
    }

    /**
     * @Route("/users/{id<\d+>}/", name="numbernine_admin_users_update_item", options={"expose"=true}, methods={"PUT"})
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $this->denyAccessUnlessGranted(Capabilities::EDIT_USERS);

        /** @var array $data */
        $data = $request->request->get('user');
        $this->setFields($user, $data);

        $this->entityManager->flush();

        return $this->responseFactory->createSerializedJsonResponse($user, ['groups' => ['user_get']]);
    }

    /**
     * @Route("/users/create/", name="numbernine_admin_users_create_item", options={"expose"=true}, methods={"POST"})
     */
    public function create(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $this->denyAccessUnlessGranted(Capabilities::CREATE_USERS);

        /** @var array $data */
        $data = $request->request->get('user');
        $user = (new User())->setUsername($data['username']);
        $user->setPassword($passwordEncoder->encodePassword($user, $data['password']));
        $this->setFields($user, $data);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->responseFactory->createSerializedJsonResponse($user, ['groups' => ['user_get']]);
    }

    private function setFields(User $user, array $data): void
    {
        $user
            ->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setDisplayNameFormat($data['displayNameFormat'])
            ->setEmail($data['email']);

        $userRolesIds = array_values(array_map(fn(UserRole $role) => $role->getId(), $user->getUserRoles()->toArray()));
        $submittedRolesIds = array_values(array_map(fn($role) => $role['id'], $data['userRoles']));
        $userRoles = $this->userRoleRepository->findBy(['id' => $submittedRolesIds]);

        sort($userRolesIds);
        sort($submittedRolesIds);

        if ($userRolesIds === $submittedRolesIds) {
            return;
        }

        $this->denyAccessUnlessGranted(Capabilities::PROMOTE_USERS);

        $user->getUserRoles()->clear();

        foreach ($userRoles as $userRole) {
            $user->addUserRole($userRole);
        }
    }
}
