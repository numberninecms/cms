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

namespace NumberNine\Controller\Admin\Ui\UserRole;

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Entity\UserRole;
use NumberNine\Form\Admin\UserRole\AdminUserRoleFormType;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Security\Capabilities;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[\Symfony\Component\Routing\Annotation\Route(path: '/users/roles/new/', name: 'numbernine_admin_user_role_create', methods: ['GET', 'POST'], priority: 10)]
final class UserRoleCreateAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ): Response {
        $this->denyAccessUnlessGranted(Capabilities::MANAGE_ROLES);

        $userRole = new UserRole();
        $form = $this->createForm(AdminUserRoleFormType::class, $userRole);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($userRole);
            $entityManager->flush();

            return $this->redirectToRoute('numbernine_admin_user_role_index', [], Response::HTTP_SEE_OTHER);
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);

        return $this->render('@NumberNine/admin/user_role/new.html.twig', [
            'form' => $form->createView(),
        ], $response);
    }
}
