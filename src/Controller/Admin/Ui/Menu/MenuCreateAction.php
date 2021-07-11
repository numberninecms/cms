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

namespace NumberNine\Controller\Admin\Ui\Menu;

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Entity\Menu;
use NumberNine\Form\Admin\Menu\AdminMenuFormType;
use NumberNine\Model\Admin\AdminController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/menus/new/", name="numbernine_admin_menu_create", methods={"GET", "POST"})
 */
final class MenuCreateAction extends AbstractController implements AdminController
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager): Response
    {
        $menu = new Menu();
        $form = $this->createForm(AdminMenuFormType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($menu);
            $entityManager->flush();
            $this->addFlash('success', 'New menu created successfully.');

            return $this->redirectToRoute(
                'numbernine_admin_menu_edit',
                [
                    'id' => $menu->getId()
                ],
                Response::HTTP_SEE_OTHER
            );
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);

        return $this->render('@NumberNine/admin/menu/new.html.twig', [
            'form' => $form->createView(),
        ], $response);
    }
}
