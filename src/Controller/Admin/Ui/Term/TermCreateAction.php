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
use NumberNine\Entity\Taxonomy;
use NumberNine\Entity\Term;
use NumberNine\Form\Admin\Term\AdminTermFormType;
use NumberNine\Model\Admin\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Inflector\EnglishInflector;

/**
 * @ParamConverter("taxonomy", options={"mapping": {"taxonomy": "name"}})
 */
#[Route(path: '/taxonomy/{taxonomy}/term/', name: 'numbernine_admin_term_create', methods: ['GET', 'POST'])]
final class TermCreateAction extends AbstractController implements AdminController
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager, Taxonomy $taxonomy): Response
    {
        $inflector = new EnglishInflector();
        $term = (new Term())->setTaxonomy($taxonomy);
        $form = $this->createForm(AdminTermFormType::class, $term);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($term);
            $entityManager->flush();
            $this->addFlash('success', 'New term created successfully.');

            return $this->redirectToRoute(
                'numbernine_admin_term_edit',
                [
                    'taxonomy' => $taxonomy->getName(),
                    'id' => $term->getId(),
                ],
                Response::HTTP_SEE_OTHER
            );
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);

        return $this->render('@NumberNine/admin/term/new.html.twig', [
            'taxonomy' => $taxonomy,
            'taxonomy_plural_name' => (string) current($inflector->pluralize((string) $taxonomy->getName())),
            'form' => $form->createView(),
        ], $response);
    }
}
