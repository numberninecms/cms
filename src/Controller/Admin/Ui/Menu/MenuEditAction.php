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
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Exception;
use NumberNine\Content\ContentService;
use NumberNine\Entity\Menu;
use NumberNine\Form\Admin\Menu\AdminMenuFormType;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Pagination\Paginator;
use NumberNine\Repository\ContentEntityRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/menus/{id}/', name: 'numbernine_admin_menu_edit', methods: ['GET', 'POST'])]
final class MenuEditAction extends AbstractController implements AdminController
{
    private const ITEMS_PER_PAGE = 5;
    private ?Request $request;

    public function __construct(
        private ContentService $contentService,
        private ContentEntityRepository $contentEntityRepository,
        RequestStack $requestStack
    ) {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function __invoke(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        Menu $menu
    ): Response {
        $form = $this->createForm(AdminMenuFormType::class, $menu, ['mode' => 'edit']);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($form->getClickedButton()->getName() === 'delete') { /** @phpstan-ignore-line */
                    $name = $menu->getName();
                    $entityManager->remove($menu);
                    $entityManager->flush();

                    $this->addFlash('success', sprintf('Menu "%s" have been successfully deleted.', $name,));

                    return $this->redirectToRoute('numbernine_admin_menu_index', [], Response::HTTP_SEE_OTHER);
                }

                $entityManager->persist($menu);
                $entityManager->flush();

                $this->addFlash('success', 'Menu successfully saved.');

                return $this->redirectToRoute(
                    'numbernine_admin_menu_edit',
                    [
                        'id' => $menu->getId(),
                    ],
                    Response::HTTP_SEE_OTHER
                );
            } catch (Exception $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', 'An unknown error occured.');
            }
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);

        return $this->render('@NumberNine/admin/menu/edit.html.twig', [
            'form' => $form->createView(),
            'content_types' => $this->contentService->getContentTypes(),
            'entities' => $this->getEntities(),
            'pages' => $this->getPages(),
        ], $response);
    }

    private function getEntities(): array
    {
        $entities = [];

        foreach ($this->contentService->getContentTypes() as $contentType) {
            $type = $contentType->getName();
            $entities[$type] = new Paginator(new DoctrinePaginator(
                $this->contentEntityRepository->getSimplePaginatedCollectionQueryBuilder(
                    $type,
                    $this->request
                        ? ((int) $this->request->query->get($type . '_page', '1') - 1) * self::ITEMS_PER_PAGE
                        : 0,
                    self::ITEMS_PER_PAGE,
                )
            ));
        }

        return $entities;
    }

    private function getPages(): array
    {
        $pages = [];

        foreach ($this->contentService->getContentTypes() as $contentType) {
            $type = $contentType->getName();
            $pages[$type] = $this->request
                ? (int) $this->request->query->get($type . '_page', '1')
                : 1;
        }

        return $pages;
    }
}
