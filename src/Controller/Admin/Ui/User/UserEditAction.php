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

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Entity\User;
use NumberNine\Form\Admin\User\AdminUserFormType;
use NumberNine\Model\Admin\AdminController;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users/{id}/", name="numbernine_admin_user_edit", methods={"GET", "POST"})
 */
final class UserEditAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        User $user
    ): Response {
        /** @var Form $form */
        $form = $this->createForm(AdminUserFormType::class, $user, ['mode' => 'edit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($form->getClickedButton()->getName() === 'delete') { // @phpstan-ignore-line
                    $entityManager->remove($user);
                    $entityManager->flush();

                    $this->addFlash('success', sprintf(
                        'User %s have been successfully deleted.',
                        $user->getId(),
                    ));

                    return $this->redirectToRoute('numbernine_admin_user_index', [], Response::HTTP_SEE_OTHER);
                } else {
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $this->addFlash('success', 'Term successfully saved.');

                    return $this->redirectToRoute(
                        'numbernine_admin_user_edit',
                        ['id' => $user->getId()],
                        Response::HTTP_SEE_OTHER,
                    );
                }
            } catch (\Exception $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', 'An unknown error occured.');
            }
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);

        return $this->render('@NumberNine/admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ], $response);
    }
}
