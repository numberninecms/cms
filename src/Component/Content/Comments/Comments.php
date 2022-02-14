<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Component\Content\Comments;

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Entity\Comment;
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\User;
use NumberNine\Event\CurrentContentEntityEvent;
use NumberNine\Form\Content\CommentFormType;
use NumberNine\Model\Component\AbstractFormComponent;
use NumberNine\Model\Component\Features\ContentEntityPropertyTrait;
use NumberNine\Repository\CommentRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class Comments extends AbstractFormComponent
{
    use ContentEntityPropertyTrait;
    protected array $supportedTemplates = ['post/single.html.twig'];

    private ?ContentEntity $currentEntity = null;

    public function __construct(
        private CommentRepository $commentRepository,
        private FormFactoryInterface $formFactory,
        private TokenStorageInterface $tokenStorage,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return array_merge(parent::getSubscribedEvents(), [
            CurrentContentEntityEvent::class => 'setCurrentEntity',
        ]);
    }

    public function setCurrentEntity(CurrentContentEntityEvent $event): void
    {
        $this->currentEntity = $event->getContentEntity();
    }

    public function getTemplateParameters(): array
    {
        return array_merge(parent::getTemplateParameters(), [
            'comments' => $this->getComments(),
        ]);
    }

    protected function getForm(): FormInterface
    {
        if (!$this->form) {
            $comment = new Comment();
            $user = ($token = $this->tokenStorage->getToken()) !== null ? $token->getUser() : null;

            if ($user instanceof User) {
                $comment->setAuthor($user);
            }

            $comment->setContentEntity($this->currentEntity);

            $this->form = $this->formFactory->createBuilder(CommentFormType::class, $comment)
                ->add('submit', SubmitType::class)
                ->getForm()
            ;
        }

        return $this->form;
    }

    protected function handleSubmittedAndValidForm(Request $request): Response
    {
        $this->entityManager->persist($this->getForm()->getData());
        $this->entityManager->flush();

        return new RedirectResponse($request->getPathInfo());
    }

    private function getComments(): array
    {
        if (!$this->contentEntity) {
            return [];
        }

        return $this->commentRepository->findByContentEntityId((int) $this->contentEntity->getId());
    }
}
