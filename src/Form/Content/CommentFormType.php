<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Form\Content;

use NumberNine\Entity\Comment;
use NumberNine\Entity\User;
use NumberNine\Form\DataTransformer\CommentToNumberTransformer;
use NumberNine\Form\DataTransformer\ContentEntityToNumberTransformer;
use NumberNine\Form\DataTransformer\UserToNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class CommentFormType extends AbstractType
{
    private TokenStorageInterface $tokenStorage;
    private CommentToNumberTransformer $commentToNumberTransformer;
    private ContentEntityToNumberTransformer $contentEntityToNumberTransformer;
    private UserToNumberTransformer $userToNumberTransformer;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        CommentToNumberTransformer $commentToNumberTransformer,
        ContentEntityToNumberTransformer $contentEntityToNumberTransformer,
        UserToNumberTransformer $userToNumberTransformer
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->commentToNumberTransformer = $commentToNumberTransformer;
        $this->contentEntityToNumberTransformer = $contentEntityToNumberTransformer;
        $this->userToNumberTransformer = $userToNumberTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->tokenStorage->getToken() !== null ? $this->tokenStorage->getToken()->getUser() : null;

        $builder
            ->add('content', null, ['label' => 'Comment'])
            ->add('contentEntity', HiddenType::class)
            ->add('parent', HiddenType::class, ['required' => false]);

        if ($user instanceof User) {
            $builder->add('author', HiddenType::class);

            $builder
                ->get('author')
                ->addModelTransformer($this->userToNumberTransformer);
        } else {
            $builder
                ->add('guestAuthorName', null)
                ->add('guestAuthorEmail', null)
                ->add('guestAuthorUrl', null, ['required' => false]);
        }

        $builder
            ->get('contentEntity')
            ->addModelTransformer($this->contentEntityToNumberTransformer);

        $builder
            ->get('parent')
            ->addModelTransformer($this->commentToNumberTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
