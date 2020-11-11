<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Form\User;

use NumberNine\Form\Type\CheckboxVirginType;
use NumberNine\Form\Type\HiddenVirginType;
use NumberNine\Form\Type\PasswordVirginType;
use NumberNine\Form\Type\TextVirginType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextVirginType::class)
            ->add('password', PasswordVirginType::class)
            ->add('_remember_me', CheckboxVirginType::class, ['label' => 'Remember me', 'required' => false])
            ->add('_csrf_token', HiddenVirginType::class)
            ->add('submit', SubmitType::class, ['label' => 'Sign in', 'attr' => ['forgotten' => true]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'csrf_protection' => true,
            ]
        );
    }
}
