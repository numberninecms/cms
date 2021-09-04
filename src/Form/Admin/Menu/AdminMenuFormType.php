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

namespace NumberNine\Form\Admin\Menu;

use NumberNine\Entity\Menu;
use NumberNine\Form\DataTransformer\SerializerTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AdminMenuFormType extends AbstractType
{
    public function __construct(private SerializerTransformer $serializerTransformer)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name');

        if ($options['mode'] === 'create') {
            $builder->add('create', SubmitType::class, ['attr' => ['value' => 'create']]);
        } else {
            $builder
                ->add('menuItems', HiddenType::class)
                ->add('save', SubmitType::class, ['attr' => ['value' => 'save']])
                ->add('delete', SubmitType::class, ['attr' => ['value' => 'delete']])
            ;

            $builder->get('menuItems')->addModelTransformer($this->serializerTransformer);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
            'mode' => 'create',
        ]);

        $resolver->setRequired('mode');
        $resolver->setAllowedValues('mode', ['create', 'edit']);
    }
}
