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

namespace NumberNine\Controller\Admin\Ui\Term;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use NumberNine\Entity\Taxonomy;
use NumberNine\Form\Admin\Term\AdminTermIndexFormType;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\Pagination\PaginationParameters;
use NumberNine\Repository\TermRepository;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Inflector\EnglishInflector;

/**
 * @ParamConverter("taxonomy", options={"mapping": {"taxonomy": "name"}})
 */
#[Route(path: '/taxonomy/{taxonomy}/', name: 'numbernine_admin_term_index', methods: ['GET', 'POST'])]
final class TermIndexAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        TermRepository $termRepository,
        LoggerInterface $logger,
        Taxonomy $taxonomy
    ): Response {
        $inflector = new EnglishInflector();

        /** @var PaginationParameters $paginationParameters */
        $paginationParameters = $serializer->denormalize(
            $request->query->all(),
            PaginationParameters::class,
            null,
            [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]
        );

        $queryBuilder = $termRepository->getByTaxonomyPaginatedCollectionQueryBuilder(
            (string) $taxonomy->getName(),
            $paginationParameters,
        );

        $terms = new Paginator($queryBuilder, true);

        $form = $this->createForm(AdminTermIndexFormType::class, null, ['terms' => $terms]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $checkedIds = array_map(
                fn ($name): int => (int) str_replace('term_', '', $name),
                array_keys(array_filter($form->getData()))
            );

            try {
                $termRepository->removeCollection($checkedIds);
                $entityManager->flush();

                $this->addFlash('success', 'Terms have been deleted successfully.');

                return $this->redirectToRoute(
                    'numbernine_admin_term_index',
                    array_merge(['taxonomy' => $taxonomy->getName()], $request->query->all()),
                    Response::HTTP_SEE_OTHER,
                );
            } catch (Exception $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', 'An unknown error occured.');
            }
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);

        return $this->render('@NumberNine/admin/term/index.html.twig', [
            'taxonomy' => $taxonomy,
            'taxonomy_plural_name' => (string) current($inflector->pluralize((string) $taxonomy->getName())),
            'terms' => $terms,
            'form' => $form->createView(),
        ], $response);
    }
}
