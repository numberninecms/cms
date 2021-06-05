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

use NumberNine\Content\ContentService;
use NumberNine\Entity\ContentEntity;
use NumberNine\Event\HiddenCustomFieldsEvent;
use NumberNine\Form\DataTransformer\AssociativeArrayToKeyValueCollectionTransformer;
use NumberNine\Form\Type\KeyValueCollectionType;
use NumberNine\Form\Type\MediaFileType;
use NumberNine\Form\Type\TinyEditorType;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Theme\TemplateResolver;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
    private AssociativeArrayToKeyValueCollectionTransformer $associativeArrayToKeyValueCollectionTransformer;
    private ContentService $contentService;
    private TemplateResolver $templateResolver;
    private EventDispatcherInterface $eventDispatcher;
    private HiddenCustomFieldsEvent $hiddenCustomFieldsEvent;
    private ContentEntity $originalEntity;

    public function __construct(
        AssociativeArrayToKeyValueCollectionTransformer $transformer,
        ContentService $contentService,
        TemplateResolver $templateResolver,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->associativeArrayToKeyValueCollectionTransformer = $transformer;
        $this->contentService = $contentService;
        $this->templateResolver = $templateResolver;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('content', TinyEditorType::class, ['required' => false])
            ->add('seoTitle', null, ['required' => false])
            ->add('seoDescription', TextareaType::class, ['required' => false])
            ->add('customFields', KeyValueCollectionType::class, [
                'add_new_label' => 'Add new custom field',
                'required' => false,
            ])
            ->add('status', ChoiceType::class, ['choices' => [
                'Draft' => PublishingStatusInterface::STATUS_DRAFT,
                'Private' => PublishingStatusInterface::STATUS_PRIVATE,
                'Pending review' => PublishingStatusInterface::STATUS_PENDING_REVIEW,
                'Publish' => PublishingStatusInterface::STATUS_PUBLISH,
            ]])
            ->add('submit', SubmitType::class)
        ;

        $customFields = $builder->get('customFields');
        $customFieldsOptions = $customFields->getOptions();
        $customFieldsOptions['entry_options'] = array_merge_recursive_fixed($customFieldsOptions['entry_options'], [
            'key_label' => 'Name',
        ]);

        $builder->add($customFields->getName(), KeyValueCollectionType::class, $customFieldsOptions);

        $builder
            ->get('customFields')
            ->addModelTransformer($this->associativeArrayToKeyValueCollectionTransformer);

        /** @var HiddenCustomFieldsEvent $hiddenCustomFieldsEvent */
        $hiddenCustomFieldsEvent = $this->eventDispatcher->dispatch(new HiddenCustomFieldsEvent([
            'page_template',
        ]));
        $this->hiddenCustomFieldsEvent = $hiddenCustomFieldsEvent;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'backupData']);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'addTemplateField']);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'addFeaturedImageField']);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'addHiddenCustomFields']);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'transformCustomFields']);
        $builder->addEventListener(FormEvents::POST_SET_DATA, [$this, 'setUnmappedData']);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'transformSeo']);
        $builder->addEventListener(FormEvents::SUBMIT, [$this, 'transformTemplate']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContentEntity::class,
        ]);
    }

    public function backupData(FormEvent $event): void
    {
        $this->originalEntity = clone $event->getData();
    }

    public function transformCustomFields(FormEvent $event): void
    {
        /** @var ContentEntity $entity */
        $entity = $event->getData();

        $customFields = [];

        foreach (($entity->getCustomFields() ?? []) as $key => $value) {
            if (is_array($value) || in_array($key, $this->hiddenCustomFieldsEvent->getFieldsToHide())) {
                continue;
            }

            $customFields[] = [
                'key' => $key,
                'value' => $value,
            ];
        }

        $entity->setCustomFields($customFields);

        $event->setData($entity);
    }

    public function addHiddenCustomFields(FormEvent $event): void
    {
        $form = $event->getForm();

        foreach ($this->hiddenCustomFieldsEvent->getFieldsToHide() as $fieldName) {
            if (in_array($fieldName, ['page_template'])) {
                continue;
            }

            $form->add($fieldName, HiddenType::class, ['mapped' => false]);
        }
    }

    public function setUnmappedData(FormEvent $event): void
    {
        $form = $event->getForm();

        foreach ($this->hiddenCustomFieldsEvent->getFieldsToHide() as $fieldName) {
            if (in_array($fieldName, ['page_template'])) {
                continue;
            }

            $form[$fieldName]->setData($this->originalEntity->getCustomField($fieldName));
        }

        $form['customTemplate']->setData($this->originalEntity->getCustomField('page_template'));
    }

    public function transformSeo(FormEvent $event): void
    {
        $form = $event->getForm();

        /** @var ContentEntity $entity */
        $entity = $form->getData();

        $entity->setSeoTitle($form['seoTitle']->getData());
        $entity->setSeoDescription($form['seoTitle']->getData());

        $form->setData($entity);
    }

    public function addTemplateField(FormEvent $event): void
    {
        $form = $event->getForm();
        /** @var ContentEntity $entity */
        $entity = $event->getData();
        $contentType = $this->contentService->getContentType((string)$entity->getCustomType());

        $candidates = array_merge(
            $this->templateResolver->getContentEntitySingleTemplateCandidates($contentType),
            $this->templateResolver->getContentEntityIndexTemplateCandidates(),
        );

        $form->add('customTemplate', ChoiceType::class, [
            'choices' => array_flip($candidates),
            'mapped' => false,
        ]);
    }

    public function transformTemplate(FormEvent $event): void
    {
        $form = $event->getForm();

        /** @var ContentEntity $entity */
        $entity = $form->getData();

        $entity->addCustomField('page_template', $form['customTemplate']->getData());
    }

    public function addFeaturedImageField(FormEvent $event): void
    {
        $form = $event->getForm();
        /** @var ContentEntity $entity */
        $entity = $event->getData();

        if (property_exists($entity, 'featuredImage')) {
            $form->add('featuredImage', MediaFileType::class);
        }
    }
}
