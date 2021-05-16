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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class KeyValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('key', null, ['label' => $options['key_label']])
            ->add('value', null, ['label' => $options['value_label']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'key_label' => 'Key',
            'value_label' => 'Value',
            'delete_button' => false,
        ]);

        $resolver->setAllowedTypes('key_label', 'string');
        $resolver->setAllowedTypes('value_label', 'string');
        $resolver->setAllowedTypes('delete_button', 'bool');
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['delete_button'] = $options['delete_button'];
    }
}
