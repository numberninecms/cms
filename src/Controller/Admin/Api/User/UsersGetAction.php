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

use Doctrine\ORM\Tools\Pagination\Paginator;
use NumberNine\Http\ResponseFactory;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\Pagination\PaginationParameters;
use NumberNine\Repository\UserRepository;
use NumberNine\Security\Capabilities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: '/users/', name: 'numbernine_admin_users_get_collection', options: ['expose' => true], methods: [
    'GET',
])]
final class UsersGetAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        ResponseFactory $responseFactory,
        SerializerInterface $serializer,
        UserRepository $userRepository
    ): JsonResponse {
        $this->denyAccessUnlessGranted(Capabilities::LIST_USERS);

        /** @var PaginationParameters $paginationParameters */
        $paginationParameters = $serializer->denormalize(
            $request->query->all(),
            PaginationParameters::class,
            null,
            [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]
        );

        $queryBuilder = $userRepository->getPaginatedCollectionQueryBuilder($paginationParameters);
        $users = new Paginator($queryBuilder, true);

        return $responseFactory->createSerializedPaginatedJsonResponse($users, ['groups' => ['user_get']]);
    }
}
