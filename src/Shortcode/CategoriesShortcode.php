<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Entity\Term;
use NumberNine\Event\CurrentRequestTermEvent;
use NumberNine\Model\PageBuilder\Control\OnOffSwitchControl;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use NumberNine\Repository\TermRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @Shortcode(
 *     name="categories",
 *     label="Categories",
 *     description="Displays the posts categories.",
 *     icon="category"
 * )
 */
final class CategoriesShortcode extends AbstractShortcode implements
    EditableShortcodeInterface,
    EventSubscriberInterface
{
    private TermRepository $termRepository;
    private ?Term $term = null;

    public static function getSubscribedEvents(): array
    {
        return [
            CurrentRequestTermEvent::class => 'setTerm',
        ];
    }

    public function __construct(TermRepository $termRepository)
    {
        $this->termRepository = $termRepository;
    }

    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('title')
            ->add('showPostCounts', OnOffSwitchControl::class)
            ->add('showIfEmpty', OnOffSwitchControl::class)
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'title' => 'Categories',
            'showPostCounts' => false,
            'showIfEmpty' => false,
        ]);
    }

    public function processParameters(array $parameters): array
    {
        return [
            'title' => $parameters['title'],
            'categories' => $this->getCategories(),
            'showIfEmpty' => $parameters['showIfEmpty'],
            'showPostCounts' => $parameters['showPostCounts'],
            'term' => $this->term,
        ];
    }

    public function setTerm(CurrentRequestTermEvent $event): void
    {
        $this->term = $event->getTerm();
    }

    /**
     * @return Term[]
     */
    private function getCategories(): array
    {
        return $this->termRepository->findByTaxonomyName('category', true);
    }
}
