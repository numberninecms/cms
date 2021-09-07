<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Api\Taxonomy;

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Entity\Term;
use NumberNine\Form\Taxonomy\TermFormType;
use NumberNine\Http\ResponseFactory;
use NumberNine\Model\Admin\AdminController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractTermController extends AbstractController implements AdminController
{
    private ?Request $request;

    public function __construct(
        RequestStack $requestStack,
        private EntityManagerInterface $entityManager,
        private ResponseFactory $responseFactory
    ) {
        $this->request = $requestStack->getCurrentRequest();
    }

    protected function handle(Term $term): JsonResponse
    {
        if (!$this->request) {
            return $this->responseFactory->createErrorJsonResponse('No "Request" object.');
        }

        $form = $this->createForm(TermFormType::class, $term);

        /** @var array $termData */
        $termData = $this->request->request->get('term');
        $form->submit($termData);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($term);
            $this->entityManager->flush();

            return $this->responseFactory->createSerializedJsonResponse($term, ['groups' => ['term_get']]);
        }

        return $this->responseFactory->createErrorJsonResponse((string) $form->getErrors(true));
    }
}
