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
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\User;
use NumberNine\Form\DataTransformer\CommentToNumberTransformer;
use NumberNine\Form\DataTransformer\ContentEntityToNumberTransformer;
use NumberNine\Form\DataTransformer\UserToNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

final class CommentFormType extends AbstractType
{
    public function __construct(private TokenStorageInterface $tokenStorage, private CommentToNumberTransformer $commentToNumberTransformer, private ContentEntityToNumberTransformer $contentEntityToNumberTransformer, private UserToNumberTransformer $userToNumberTransformer)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = ($token = $this->tokenStorage->getToken()) !== null ? $token->getUser() : null;

        $builder
            ->add('content', TextareaType::class, ['label' => 'Comment', 'constraints' => [new NotBlank()]])
            ->add('contentEntity', HiddenType::class, ['constraints' => [
                new NotNull(),
                new Type(ContentEntity::class),
            ]])
            ->add('parent', HiddenType::class, ['required' => false])
        ;

        if ($user instanceof User) {
            $builder->add('author', HiddenType::class, ['constraints' => [new NotNull(), new Type(User::class)]]);

            $builder
                ->get('author')
                ->addModelTransformer($this->userToNumberTransformer)
            ;
        } else {
            $builder
                ->add('guestAuthorName', null, ['label' => 'Name',  'required' => true, 'constraints' => [
                    new NotBlank(),
                ]])
                ->add('guestAuthorEmail', EmailType::class, ['label' => 'Email', 'required' => true, 'constraints' => [
                    new NotBlank(),
                    new Email(),
                ]])
                ->add('guestAuthorUrl', null, ['label' => 'Website', 'required' => false])
            ;
        }

        $builder
            ->get('contentEntity')
            ->addModelTransformer($this->contentEntityToNumberTransformer)
        ;

        $builder
            ->get('parent')
            ->addModelTransformer($this->commentToNumberTransformer)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
