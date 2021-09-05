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
use Doctrine\ORM\EntityNotFoundException;
use NumberNine\Entity\UserRole;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Repository\UserRoleRepository;
use NumberNine\Security\Capabilities;
use NumberNine\Http\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[\Symfony\Component\Routing\Annotation\Route(path: '/users/roles/', name: 'numbernine_admin_user_roles_update_collection', options: ['expose' => true], methods: ['PUT'])]
final class UserRolesUpdateAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ResponseFactory $responseFactory,
        UserRoleRepository $userRoleRepository
    ): JsonResponse {
        $this->denyAccessUnlessGranted(Capabilities::MANAGE_ROLES);

        $roles = $request->get('roles', []);

        foreach ($roles as $role) {
            if (!empty($role['id'])) {
                $userRole = $userRoleRepository->find($role['id']);

                if (!$userRole) {
                    throw new EntityNotFoundException(sprintf('User role with ID %d not found', $role['id']));
                }

                $userRole
                    ->setName($role['name'])
                    ->setCapabilities($role['capabilities']);
            } else {
                /** @var UserRole $userRole */
                $userRole = $serializer->denormalize(
                    $role,
                    UserRole::class,
                    null,
                    [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]
                );
                $entityManager->persist($userRole);
            }
        }

        $entityManager->flush();

        return $responseFactory->createSuccessJsonResponse();
    }
}
