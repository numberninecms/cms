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
use Doctrine\ORM\EntityNotFoundException;
use NumberNine\Form\Admin\UserRole\AdminUserRoleIndexFormType;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Repository\UserRoleRepository;
use NumberNine\Security\Capabilities;
use NumberNine\Security\CapabilityStore;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users/roles/", name="numbernine_admin_user_role_index", methods={"GET", "POST"}, priority="10")
 */
final class UserRoleIndexAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        UserRoleRepository $userRoleRepository,
        CapabilityStore $capabilityStore,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ): Response {
        $this->denyAccessUnlessGranted(Capabilities::MANAGE_ROLES);

        $roles = $userRoleRepository->findAll();
        $capabilities = $capabilityStore->getAllAvailableCapabilities();
        $builtInRoles = ['Subscriber', 'Contributor', 'Author', 'Editor', 'Administrator', 'Banned'];

        /** @var Form $form */
        $form = $this->createForm(AdminUserRoleIndexFormType::class, null, [
            'roles' => $roles,
            'capabilities' => $capabilities,
            'built_in_roles' => $builtInRoles,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clickedButtonName = $form->getClickedButton()->getName(); // @phpstan-ignore-line

            if ($clickedButtonName === 'save') {
                try {
                    $entityManager->flush();
                    $this->addFlash('success', 'Roles saved successfully.');

                    return $this->redirectToRoute('numbernine_admin_user_role_index', [], Response::HTTP_SEE_OTHER);
                } catch (\Exception $e) {
                    $logger->error($e->getMessage());
                    $this->addFlash('error', "Roles couldn't be saved.");
                }
            } else {
                try {
                    $id = (int)str_replace('delete_', '', $clickedButtonName);
                    $userRole = $userRoleRepository->find($id);

                    if (!$userRole) {
                        throw new EntityNotFoundException();
                    }

                    $entityManager->remove($userRole);
                    $entityManager->flush();
                    $this->addFlash('success', 'Role deleted successfully.');

                    return $this->redirectToRoute('numbernine_admin_user_role_index', [], Response::HTTP_SEE_OTHER);
                } catch (\Exception $e) {
                    $logger->error($e->getMessage());
                    $this->addFlash('error', 'Unable to delete the role.');
                }
            }
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);

        return $this->render('@NumberNine/admin/user_role/index.html.twig', [
            'roles' => $roles,
            'built_in_roles' => $builtInRoles,
            'capabilities' => $capabilities,
            'form' => $form->createView(),
        ], $response);
    }
}
