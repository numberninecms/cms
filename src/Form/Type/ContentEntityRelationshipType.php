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

use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\ContentEntityRelationship;
use NumberNine\Exception\InvalidManyToOneRelationshipTypeException;
use NumberNine\Form\DataTransformer\ContentEntityToNumberTransformer;
use NumberNine\Repository\ContentEntityRelationshipRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ContentEntityRelationshipType extends AbstractType
{
    private ContentEntityRelationshipRepository $contentEntityRelationshipRepository;
    private ContentEntityToNumberTransformer $contentEntityToNumberTransformer;

    public function __construct(
        ContentEntityRelationshipRepository $contentEntityRelationshipRepository,
        ContentEntityToNumberTransformer $contentEntityToNumberTransformer
    ) {
        $this->contentEntityRelationshipRepository = $contentEntityRelationshipRepository;
        $this->contentEntityToNumberTransformer = $contentEntityToNumberTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('child', $options['form_type'], $options['form_type_options'])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'initialize']);
        $builder->addEventListener(FormEvents::SUBMIT, [$this, 'submit']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContentEntityRelationship::class,
            'form_type' => EntityType::class,
            'form_type_options' => [
                'class' => ContentEntity::class,
                'choice_label' => 'title',
            ],
        ]);

        $resolver->setRequired(['name', 'parent']);
        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('form_type', 'string');
        $resolver->setAllowedTypes('form_type_options', 'array');
        $resolver->setAllowedTypes('parent', ContentEntity::class);
    }

    public function initialize(FormEvent $event): void
    {
        $options = $event->getForm()->getConfig()->getOptions();
        /** @var ContentEntity $parent */
        $parent = $options['parent'];

        $relationships = $this->contentEntityRelationshipRepository->findBy([
            'parent' => $parent->getId(),
            'name' => $options['name'],
        ]);

        if (count($relationships) > 1) {
            throw new InvalidManyToOneRelationshipTypeException($options['name']);
        }

        if (count($relationships) === 0) {
            $data = new ContentEntityRelationship();
            $data->setParent($parent);
            $data->setName($options['name']);
        } else {
            $data = $relationships[0];
        }

        $event->setData($data);
    }

    public function submit(FormEvent $event): void
    {
        $form = $event->getForm();
        $options = $event->getForm()->getConfig()->getOptions();
        /** @var ContentEntity $parent */
        $parent = $options['parent'];

        if (
            !$parent->getChildren()->contains($form->getData())
            && $form->getData()->getChild() instanceof ContentEntity
        ) {
            $parent->addChild($form->getData());
        }
    }
}
