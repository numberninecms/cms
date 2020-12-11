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

use NumberNine\Annotation\Shortcode;
use NumberNine\Annotation\Shortcode\Exclude;
use NumberNine\Entity\Term;
use NumberNine\Event\CurrentRequestTermEvent;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Repository\TermRepository;

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

    public static function getSubscribedEvents(): array
    {
        return array_merge(
            parent::getSubscribedEvents(),
            [
                CurrentRequestTermEvent::class => 'setTerm',
            ]
        );
    }

    public function __construct(TermRepository $termRepository)
    {
        $this->termRepository = $termRepository;
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

    /**
     * @param CategoriesShortcodeData $data
     */
    public function process($data): void
    {
        $data->setCategories($this->getCategories());
        $data->setTerm($this->term);
    }
}
