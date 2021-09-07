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
use Exception;
use NumberNine\Form\Admin\Menu\AdminMenuIndexFormType;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Repository\MenuRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: '/menus/', name: 'numbernine_admin_menu_index')]
final class MenuIndexAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        MenuRepository $menuRepository,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ): Response {
        $menus = $menuRepository->findAll();
        $form = $this->createForm(AdminMenuIndexFormType::class, null, ['menus' => $menus]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $checkedIds = array_map(
                fn ($name): int => (int) str_replace('menu_', '', $name),
                array_keys(array_filter($form->getData()))
            );

            try {
                $menuRepository->removeCollection($checkedIds);
                $entityManager->flush();

                $this->addFlash('success', 'Menus have been deleted successfully.');

                return $this->redirectToRoute('numbernine_admin_menu_index', [], Response::HTTP_SEE_OTHER);
            } catch (Exception $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', 'An unknown error occured.');
            }
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);

        return $this->render('@NumberNine/admin/menu/index.html.twig', [
            'form' => $form->createView(),
            'menus' => $menus,
        ], $response);
    }
}
