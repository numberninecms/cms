<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Form\Admin\User;

use NumberNine\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AdminUserIndexFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mode', ChoiceType::class, [
                'choices' => [
                    'Reassign all associated content to current user' => UserRepository::DELETE_MODE_REASSIGN,
                    'Delete all associated content' => UserRepository::DELETE_MODE_DELETE,
                ],
                'help' => "Comments won't be deleted, but anonymized instead.",
                'expanded' => true,
                'label' => 'Deletion mode',
            ])
            ->add('delete', SubmitType::class, ['label' => 'Delete', 'attr' => ['value' => 'delete']])
        ;

        foreach ($options['users'] as $user) {
            $builder
                ->add(sprintf('user_%d', $user->getId()), CheckboxType::class, ['required' => false])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'users' => [],
        ]);

        $resolver->setRequired('users');
    }
}
