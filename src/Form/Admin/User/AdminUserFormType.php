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

namespace NumberNine\Form\Admin\User;

use NumberNine\Entity\User;
use NumberNine\Entity\UserRole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class AdminUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('email', EmailType::class)
            ->add('firstName')
            ->add('lastName')
            ->add('userRoles', EntityType::class, [
                'class' => UserRole::class,
                'multiple' => true,
                'expanded' => true,
            ])
        ;

        if ($options['mode'] === 'create') {
            $builder
                ->add('create', SubmitType::class, ['attr' => ['value' => 'create']])
                ->add('plainPassword', PasswordType::class, [
                    'mapped' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Password'
                ])
            ;
        } else {
            $builder
                ->add('save', SubmitType::class, ['attr' => ['value' => 'save']])
                ->add('delete', SubmitType::class, ['attr' => ['value' => 'delete']])
            ;
        }

        $builder->addEventListener(FormEvents::POST_SET_DATA, [$this, 'setDisplayNameFormat']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true,
            'mode' => 'create',
        ]);

        $resolver->setRequired('mode');
        $resolver->setAllowedValues('mode', ['create', 'edit']);
    }

    public function setDisplayNameFormat(FormEvent $event): void
    {
        $form = $event->getForm();
        /** @var User $user */
        $user = $form->getData();

        $username = $user->getUsername() ?? 'Username';
        $firstName = $user->getFirstName() ?? 'First name';
        $lastName = $user->getLastName() ?? 'Last name';
        $firstLast = "$firstName $lastName";
        $lastFirst = "$lastName $firstName";

        $form->add('displayNameFormat', ChoiceType::class, ['choices' => [
            $username => 'username',
            $firstName => 'first_only',
            $lastName => 'last_only',
            $firstLast => 'first_last',
            $lastFirst => 'last_first',
        ]]);
    }
}
