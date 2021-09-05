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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[\Symfony\Component\Routing\Annotation\Route(path: '/users/new/', name: 'numbernine_admin_user_create', methods: ['GET', 'POST'])]
final class UserCreateAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {
        $user = new User();

        /** @var Form $form */
        $form = $this->createForm(AdminUserFormType::class, $user, ['mode' => 'create']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $form['plainPassword']->getData()));

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'New user created successfully.');

            return $this->redirectToRoute(
                'numbernine_admin_user_edit',
                ['id' => $user->getId()],
                Response::HTTP_SEE_OTHER,
            );
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);

        return $this->render('@NumberNine/admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ], $response);
    }
}
