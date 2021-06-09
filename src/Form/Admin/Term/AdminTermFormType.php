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

namespace NumberNine\Form\Admin\Term;

use NumberNine\Entity\Term;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AdminTermFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('slug')
            ->add('description')
        ;

        if ($options['mode'] === 'create') {
            $builder->add('create', SubmitType::class, ['attr' => ['value' => 'create']]);
        } else {
            $builder
                ->add('save', SubmitType::class, ['attr' => ['value' => 'save']])
                ->add('delete', SubmitType::class, ['attr' => ['value' => 'delete']])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Term::class,
            'mode' => 'create',
        ]);

        $resolver->setRequired('mode');
        $resolver->setAllowedValues('mode', ['create', 'edit']);
    }
}
