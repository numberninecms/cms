<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Form\Admin\Content;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AdminContentEntityCollectionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('restore', SubmitType::class, ['attr' => ['value' => 'restore']])
            ->add('delete', SubmitType::class, ['label' => 'Permanently delete', 'attr' => ['value' => 'delete']])
        ;

        foreach ($options['entities'] as $entity) {
            $builder->add(sprintf('entity_%d', $entity->getId()), CheckboxType::class, ['required' => false]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entities' => [],
        ]);
    }
}
