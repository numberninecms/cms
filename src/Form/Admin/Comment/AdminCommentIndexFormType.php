<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Form\Admin\Comment;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AdminCommentIndexFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('delete', SubmitType::class, ['label' => 'Delete', 'attr' => ['value' => 'delete']])
        ;

        if ($options['trash']) {
            $builder
                ->add('restore', SubmitType::class, ['attr' => ['value' => 'restore']])
                ->add('delete', SubmitType::class, ['label' => 'Permanently delete', 'attr' => ['value' => 'delete']])
            ;
        }

        foreach ($options['comments'] as $comment) {
            $builder
                ->add(sprintf('comment_%d', $comment->getId()), CheckboxType::class, ['required' => false])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'comments' => [],
            'trash' => false,
        ]);

        $resolver->setRequired('comments');
        $resolver->setAllowedTypes('trash', 'bool');
    }
}
