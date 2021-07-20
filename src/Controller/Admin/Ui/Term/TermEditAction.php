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
use NumberNine\Exception\InvalidTermTaxonomyException;
use NumberNine\Form\Admin\Term\AdminTermFormType;
use NumberNine\Model\Admin\AdminController;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Inflector\EnglishInflector;

/**
 * @Route("/taxonomy/{taxonomy}/term/{id}/", name="numbernine_admin_term_edit", methods={"GET", "POST"})
 * @ParamConverter("taxonomy", options={"mapping": {"taxonomy": "name"}})
 */
final class TermEditAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        Taxonomy $taxonomy,
        Term $term
    ): Response {
        if (($tax = $term->getTaxonomy()) && $tax->getId() !== $taxonomy->getId()) {
            throw new InvalidTermTaxonomyException($taxonomy, $term);
        }

        $inflector = new EnglishInflector();

        /** @var Form $form */
        $form = $this->createForm(AdminTermFormType::class, $term, ['mode' => 'edit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($form->getClickedButton()->getName() === 'delete') { // @phpstan-ignore-line
                    $entityManager->remove($term);
                    $entityManager->flush();

                    $this->addFlash('success', sprintf(
                        'Term %s have been successfully deleted.',
                        $term->getId(),
                    ));

                    return $this->redirectToRoute('numbernine_admin_term_index', [
                        'taxonomy' => $taxonomy->getName(),
                    ], Response::HTTP_SEE_OTHER);
                } else {
                    $entityManager->persist($term);
                    $entityManager->flush();

                    $this->addFlash('success', 'Term successfully saved.');

                    return $this->redirectToRoute(
                        'numbernine_admin_term_edit',
                        [
                            'taxonomy' => $taxonomy->getName(),
                            'id' => $term->getId(),
                        ],
                        Response::HTTP_SEE_OTHER
                    );
                }
            } catch (\Exception $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', 'An unknown error occured.');
            }
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);

        return $this->render('@NumberNine/admin/term/edit.html.twig', [
            'taxonomy' => $taxonomy,
            'term' => $term,
            'taxonomy_plural_name' => (string)current($inflector->pluralize((string)$taxonomy->getName())),
            'form' => $form->createView(),
        ], $response);
    }
}
