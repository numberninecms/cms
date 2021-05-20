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

namespace NumberNine\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class KeyValueCollectionType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entry_type' => KeyValueType::class,
            'entry_options' => [
                'label' => false,
                'delete_button' => true,
                'row_attr' => ['class' => 'collection-item']
            ],
            'allow_add' => true,
            'allow_delete' => true,
            'add_new_label' => 'Add new collection item',
        ]);

        $resolver->setAllowedTypes('add_new_label', 'string');
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['add_new_label'] = $options['add_new_label'];
    }

    public function getParent(): string
    {
        return CollectionType::class;
    }
}
