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

use NumberNine\Entity\ContentEntity;
use NumberNine\Form\Type\KeyValueCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function NumberNine\Common\Util\ArrayUtil\array_merge_recursive_fixed;

final class AdminContentEntityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('content', null, ['required' => false])
            ->add('seoTitle', null, ['required' => false])
            ->add('seoDescription', TextareaType::class, ['required' => false])
            ->add('customFields', KeyValueCollectionType::class, [
                'add_new_label' => 'Add new custom field',
                'required' => false,
            ])
            ->add('submit', SubmitType::class)
        ;

        $customFields = $builder->get('customFields');
        $customFieldsOptions = $customFields->getOptions();
        $customFieldsOptions['entry_options'] = array_merge_recursive_fixed($customFieldsOptions['entry_options'], [
            'key_label' => 'Name',
        ]);

        $builder->add($customFields->getName(), KeyValueCollectionType::class, $customFieldsOptions);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();

            /** @var ContentEntity $entity */
            $entity = $form->getData();

            $entity->setSeoTitle($form['seoTitle']->getData());
            $entity->setSeoDescription($form['seoTitle']->getData());
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContentEntity::class,
        ]);
    }
}
