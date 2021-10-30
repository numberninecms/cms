<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Ui\Comment;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use NumberNine\Exception\UnknownSubmitActionException;
use NumberNine\Form\Admin\Comment\AdminCommentIndexFormType;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\Pagination\PaginationParameters;
use NumberNine\Repository\CommentRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(
    path: '/comments/',
    name: 'numbernine_admin_comment_index',
    methods: ['GET', 'POST'],
    options: ['expose' => true],
)]
final class CommentIndexAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        CommentRepository $commentRepository,
        LoggerInterface $logger,
    ): Response {
        /** @var PaginationParameters $paginationParameters */
        $paginationParameters = $serializer->denormalize(
            $request->query->all(),
            PaginationParameters::class,
            null,
            [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]
        );

        $comments = new Paginator($commentRepository->getPaginatedCollectionQueryBuilder($paginationParameters), true);
        $isTrash = $paginationParameters->getStatus() === 'deleted';

        /** @var Form $form */
        $form = $this->createForm(AdminCommentIndexFormType::class, null, [
            'comments' => $comments,
            'trash' => $isTrash,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $checkedIds = array_map(
                static fn ($name): int => (int) str_replace('comment_', '', $name),
                array_keys(array_filter($form->getData()))
            );

            try {
                switch ($form->getClickedButton()->getName()) {
                    case 'restore':
                        $commentRepository->restoreCollection($checkedIds);
                        $this->addFlash('success', 'Comments have been successfully restored.');

                        break;
                    case 'spam':
                        $commentRepository->markCollectionAsSpam($checkedIds);
                        $this->addFlash('success', 'Comments have been marked as spam.');

                        break;
                    case 'delete':
                        $commentRepository->removeCollection($checkedIds);
                        $this->addFlash(
                            'success',
                            $isTrash
                                ? 'Comments have been permanently deleted.'
                                : 'Comments have been deleted and placed in trash.'
                        );

                        break;
                    case 'approve':
                        $commentRepository->approveCollection($checkedIds);
                        $this->addFlash('success', 'Comments have been successfully approved.');

                        break;
                    case 'unapprove':
                        $commentRepository->unapproveCollection($checkedIds);
                        $this->addFlash('success', 'Comments have been successfully unapproved.');

                        break;
                    default:
                        throw new UnknownSubmitActionException();
                }

                return $this->redirectToRoute(
                    'numbernine_admin_comment_index',
                    $request->query->all(),
                    Response::HTTP_SEE_OTHER,
                );
            } catch (Exception $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', 'An unknown error occured.');
            }
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);

        return $this->render('@NumberNine/admin/comment/index.html.twig', [
            'comments' => $comments,
            'form' => $form->createView(),
            'is_trash' => $isTrash,
        ], $response);
    }
}
