<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\CategoriesShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode;
use NumberNine\Annotation\Shortcode\Exclude;
use NumberNine\Entity\Term;
use NumberNine\Event\CurrentRequestTermEvent;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Repository\TermRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @Shortcode(
 *     name="categories",
 *     label="Categories",
 *     description="Displays the posts categories.",
 *     editable=true,
 *     icon="category"
 * )
 */
final class CategoriesShortcode extends AbstractShortcode
{
    private TermRepository $termRepository;
    private ?Term $term = null;

    /**
     * @Control\TextBox(label="Title")
     */
    private string $title = 'Categories';

    /**
     * @Control\OnOffSwitch(label="Show post counts")
     */
    private bool $showPostCounts = false;

    /**
     * @Control\OnOffSwitch(label="Show if empty")
     */
    private bool $showIfEmpty = false;

    public function __construct(TermRepository $termRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->termRepository = $termRepository;

        $eventDispatcher->addListener(
            CurrentRequestTermEvent::class,
            function (CurrentRequestTermEvent $event): void {
                $this->term = $event->getTerm();
            }
        );
    }

    /**
     * @return Term[]
     * @Exclude("serialization")
     */
    public function getCategories(): array
    {
        return $this->termRepository->findByTaxonomyName('category', true);
    }

    /**
     * @return Term
     * @Exclude("serialization")
     */
    public function getTerm(): ?Term
    {
        return $this->term;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getShowPostCounts(): bool
    {
        return $this->showPostCounts;
    }

    public function setShowPostCounts(bool $showPostCounts): void
    {
        $this->showPostCounts = $showPostCounts;
    }

    public function getShowIfEmpty(): bool
    {
        return $this->showIfEmpty;
    }

    public function setShowIfEmpty(bool $showIfEmpty): void
    {
        $this->showIfEmpty = $showIfEmpty;
    }
}
