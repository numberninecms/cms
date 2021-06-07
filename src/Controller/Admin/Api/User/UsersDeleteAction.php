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

use Exception;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Repository\UserRepository;
use NumberNine\Security\Capabilities;
use NumberNine\Http\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/users/", name="numbernine_admin_users_delete_collection", options={"expose"=true}, methods={"POST"})
 */
final class UsersDeleteAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        ResponseFactory $responseFactory,
        SerializerInterface $serializer,
        UserRepository $userRepository
    ): JsonResponse {
        $this->denyAccessUnlessGranted(Capabilities::DELETE_USERS);

        /** @var array $ids */
        $ids = $request->request->get('ids');
        $associatedContent = $request->request->get('associatedContent', 'reassign');

        try {
            $userRepository->removeCollection($ids, $associatedContent);
        } catch (Exception $e) {
            return $responseFactory->createErrorJsonResponse($e->getMessage());
        }

        return $responseFactory->createSuccessJsonResponse();
    }
}
