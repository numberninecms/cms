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

use Doctrine\Common\Collections\ArrayCollection;
use NumberNine\Content\ContentService;
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\ContentEntityTerm;
use NumberNine\Entity\Taxonomy;
use NumberNine\Entity\Term;
use NumberNine\Event\HiddenCustomFieldsEvent;
use NumberNine\Event\SupportedContentEntityRelationshipsEvent;
use NumberNine\Form\DataTransformer\AssociativeArrayToKeyValueCollectionTransformer;
use NumberNine\Form\Type\ContentEntityRelationshipType;
use NumberNine\Form\Type\KeyValueCollectionType;
use NumberNine\Form\Type\MediaFileType;
use NumberNine\Form\Type\TinyEditorType;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Repository\ContentEntityTermRepository;
use NumberNine\Repository\TaxonomyRepository;
use NumberNine\Repository\TermRepository;
use NumberNine\Theme\TemplateResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function NumberNine\Common\Util\ArrayUtil\array_merge_recursive_fixed;

final class AdminContentEntityEditFormType extends AbstractType
{
    private AssociativeArrayToKeyValueCollectionTransformer $associativeArrayToKeyValueCollectionTransformer;
    private ContentService $contentService;
    private TemplateResolver $templateResolver;
    private EventDispatcherInterface $eventDispatcher;
    private HiddenCustomFieldsEvent $hiddenCustomFieldsEvent;
    private ContentEntity $originalEntity;
    private TaxonomyRepository $taxonomyRepository;
    private TermRepository $termRepository;
    private ContentEntityTermRepository $contentEntityTermRepository;

    /** @var Taxonomy[]|null */
    private ?array $taxonomies = null;
    private ?array $editorExtensions = null;

    public function __construct(
        AssociativeArrayToKeyValueCollectionTransformer $transformer,
        ContentService $contentService,
        TemplateResolver $templateResolver,
        EventDispatcherInterface $eventDispatcher,
        TaxonomyRepository $taxonomyRepository,
        TermRepository $termRepository,
        ContentEntityTermRepository $contentEntityTermRepository
    ) {
        $this->associativeArrayToKeyValueCollectionTransformer = $transformer;
        $this->contentService = $contentService;
        $this->templateResolver = $templateResolver;
        $this->eventDispatcher = $eventDispatcher;
        $this->taxonomyRepository = $taxonomyRepository;
        $this->termRepository = $termRepository;
        $this->contentEntityTermRepository = $contentEntityTermRepository;
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

        $this->editorExtensions = $options['editor_extensions'];

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'backupData']);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'addTemplateField']);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'addTaxonomyFields']);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'addFeaturedImageField']);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'addHiddenCustomFields']);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'transformCustomFields']);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'addEditorExtensionsFields']);
        $builder->addEventListener(FormEvents::POST_SET_DATA, [$this, 'setCustomTemplateData']);
        $builder->addEventListener(FormEvents::POST_SET_DATA, [$this, 'setTaxonomyTermsData']);
        $builder->addEventListener(FormEvents::POST_SET_DATA, [$this, 'setEditorExtensionsData']);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'transformSeo']);
        $builder->addEventListener(FormEvents::SUBMIT, [$this, 'transformTemplate']);
        $builder->addEventListener(FormEvents::SUBMIT, [$this, 'transformEditorExtensions']);
        $builder->addEventListener(FormEvents::SUBMIT, [$this, 'submitTaxonomyTerms']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContentEntity::class,
        ]);

        $resolver->setRequired('editor_extensions');
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        /** @var ContentEntity $entity */
        $entity = $form->getData();
        $view->vars['taxonomies'] = $this->getTaxonomies($entity);
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
            if (
                \is_array($value)
                || \in_array($key, $this->hiddenCustomFieldsEvent->getFieldsToHide(), true)
                || strpos($key, 'extension.') === 0
            ) {
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
            if ($fieldName === 'page_template' || strpos($fieldName, 'extension.') === 0) {
                continue;
            }

            $form->add($fieldName, HiddenType::class, ['mapped' => false]);
            $form[$fieldName]->setData($this->originalEntity->getCustomField($fieldName));
        }
    }

    public function setCustomTemplateData(FormEvent $event): void
    {
        $form = $event->getForm();
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
            'choices' => array_merge(['Default' => null], array_flip($candidates)),
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

    public function addTaxonomyFields(FormEvent $event): void
    {
        $form = $event->getForm();
        /** @var ContentEntity $entity */
        $entity = $event->getData();

        foreach ($this->getTaxonomies($entity) as $taxonomy) {
            $form
                ->add($taxonomy->getName() . '_terms', EntityType::class, [
                    'class' => Term::class,
                    'query_builder' => function () use ($taxonomy) {
                        return $this->termRepository->findByTaxonomyNameQueryBuilder($taxonomy->getName());
                    },
                    'label' => false,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                    'mapped' => false,
                ]);
        }
    }

    public function setTaxonomyTermsData(FormEvent $event): void
    {
        $form = $event->getForm();
        /** @var ContentEntity $entity */
        $entity = $event->getData();

        foreach ($this->getTaxonomies($entity) as $taxonomy) {
            $contentEntityTerms = $this->contentEntityTermRepository->findByTaxonomyName($entity, $taxonomy->getName());
            $collection = new ArrayCollection();

            foreach ($contentEntityTerms as $contentEntityTerm) {
                $collection->add($contentEntityTerm->getTerm());
            }

            $form[$taxonomy->getName() . '_terms']->setData($collection);
        }
    }

    public function submitTaxonomyTerms(FormEvent $event): void
    {
        $form = $event->getForm();

        /** @var ContentEntity $entity */
        $entity = $form->getData();

        foreach ($this->getTaxonomies($entity) as $taxonomy) {
            $taxonomyTerms = $this->termRepository->findByTaxonomyName($taxonomy->getName());

            $contentEntityTerms = $this->contentEntityTermRepository->findByTaxonomyName($entity, $taxonomy->getName());
            $existingTermsIds = array_map(static function (ContentEntityTerm $cet) {
                $term = $cet->getTerm();
                return $term ? $term->getId() : null;
            }, $contentEntityTerms);

            $checkedIds = array_map(
                fn (Term $term) => $term->getId(),
                $form[$taxonomy->getName() . '_terms']->getData()->toArray(),
            );

            $toBeAddedIds = array_values(array_diff($checkedIds, $existingTermsIds));
            $toBeRemovedIds = array_values(array_diff($existingTermsIds, $checkedIds));

            $toBeRemoved = $entity->getContentEntityTerms()->filter(
                function (ContentEntityTerm $contentEntityTerm) use ($toBeRemovedIds) {
                    $term = $contentEntityTerm->getTerm();
                    return $term && in_array($term->getId(), $toBeRemovedIds);
                }
            );

            foreach ($toBeRemoved as $cet) {
                $entity->removeContentEntityTerm($cet);
            }

            foreach ($toBeAddedIds as $id) {
                /** @var Term $term */
                $term = current(array_filter($taxonomyTerms, fn (Term $term) => $term->getId() === $id));

                $contentEntityTerm = (new ContentEntityTerm())
                    ->setContentEntity($entity)
                    ->setTerm($term)
                ;

                $entity->addContentEntityTerm($contentEntityTerm);
            }
        }
    }

    public function addFeaturedImageField(FormEvent $event): void
    {
        $form = $event->getForm();
        /** @var ContentEntity $entity */
        $entity = $event->getData();

        /** @var SupportedContentEntityRelationshipsEvent $event */
        $event = $this->eventDispatcher->dispatch(new SupportedContentEntityRelationshipsEvent(get_class($entity)));

        if (\in_array('featured_image', $event->getRelationships())) {
            $form->add('featuredImage', ContentEntityRelationshipType::class, [
                'name' => 'featured_image',
                'form_type' => MediaFileType::class,
                'form_type_options' => [],
                'parent' => $entity,
                'mapped' => false,
            ]);
        }
    }

    private function getTaxonomies(ContentEntity $entity): array
    {
        if (!$this->taxonomies) {
            $contentType = $this->contentService->getContentType((string)$entity->getCustomType());
            $this->taxonomies = $this->taxonomyRepository->findByContentType($contentType);
        }

        return $this->taxonomies;
    }

    public function addEditorExtensionsFields(FormEvent $event): void
    {
        if (empty($this->editorExtensions)) {
            return;
        }

        $form = $event->getForm();

        foreach ($this->editorExtensions as $children) {
            foreach ($children as $child) {
                if ($child['form_type'] !== null) {
                    $form->add('extension_' . $child['name'], $child['form_type'], array_merge(
                        ['mapped' => false],
                        $child['options'],
                    ));
                }
            }
        }
    }

    public function setEditorExtensionsData(FormEvent $event): void
    {
        if (empty($this->editorExtensions)) {
            return;
        }

        $form = $event->getForm();

        foreach ($this->editorExtensions as $children) {
            foreach ($children as $child) {
                if ($child['form_type'] !== null) {
                    $data = [];

                    foreach ($this->originalEntity->getCustomFieldsStartingWith('extension.') as $field => $value) {
                        $key = (string)str_replace(sprintf('extension.%s.', $child['name']), '', $field);
                        $data[$key] = $value;
                    }

                    $form['extension_' . $child['name']]->setData($data);
                }
            }
        }
    }

    public function transformEditorExtensions(FormEvent $event): void
    {
        if (empty($this->editorExtensions)) {
            return;
        }

        $form = $event->getForm();

        /** @var ContentEntity $entity */
        $entity = $form->getData();

        foreach ($this->editorExtensions as $children) {
            foreach ($children as $child) {
                if ($child['form_type'] !== null) {
                    foreach ($form['extension_' . $child['name']]->getData() as $field => $value) {
                        $entity->addCustomField(
                            sprintf('extension.%s.%s', $child['name'], $field),
                            $value,
                        );
                    }
                }
            }
        }
    }
}
