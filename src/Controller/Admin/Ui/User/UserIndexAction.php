<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\Controller\Admin\Ui\User;

use Doctrine\ORM\Tools\Pagination\Paginator;
use NumberNine\Entity\User;
use NumberNine\Form\Admin\User\AdminUserIndexFormType;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\Pagination\PaginationParameters;
use NumberNine\Repository\PostRepository;
use NumberNine\Repository\UserRepository;
use NumberNine\Security\Capabilities;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[\Symfony\Component\Routing\Annotation\Route(path: '/users/', name: 'numbernine_admin_user_index', methods: ['GET', 'POST'])]
final class UserIndexAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        UserRepository $userRepository,
        PostRepository $postRepository,
        LoggerInterface $logger
    ): Response {
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

        $form = $this->createForm(
            AdminUserIndexFormType::class,
            ['mode' => UserRepository::DELETE_MODE_REASSIGN],
            ['users' => $users],
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted(Capabilities::DELETE_USERS);

            $checkedIds = array_map(
                fn ($name): int => (int)str_replace('user_', '', $name),
                array_keys(array_filter($form->getData()))
            );

            /** @var User $user */
            $user = $this->getUser();

            if (in_array($user->getId(), $checkedIds)) {
                $this->addFlash('error', "You can't delete yourself.");
                return $this->redirectToRoute('numbernine_admin_user_index', [], Response::HTTP_SEE_OTHER);
            }

            try {
                $userRepository->removeCollection($checkedIds, $form['mode']->getData());
                $this->addFlash('success', 'Users have been deleted successfully.');

                return $this->redirectToRoute('numbernine_admin_user_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', 'An unknown error occured.');
            }
        }

        $postCounts = [];

        foreach ($users as $user) {
            /** @var User $user */
            $postCounts[$user->getId()] = $postRepository->count(['author' => $user->getId()]);
        }

        $users->getIterator()->rewind();

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);

        return $this->render('@NumberNine/admin/user/index.html.twig', [
            'users' => $users,
            'postCounts' => $postCounts,
            'form' => $form->createView(),
        ], $response);
    }
}
