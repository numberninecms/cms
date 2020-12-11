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
use NumberNine\Content\ShortcodeData;
use NumberNine\Entity\Term;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CategoriesShortcodeData extends ShortcodeData
{
    /**
     * @Control\TextBox(label="Title")
     */
    protected string $title;

    /**
     * @Control\OnOffSwitch(label="Show post counts")
     */
    protected bool $showPostCounts;

    /**
     * @Control\OnOffSwitch(label="Show if empty")
     */
    protected bool $showIfEmpty;

    protected array $categories;
    protected ?Term $term;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'title' => 'Categories',
            'categories' => [],
            'showPostCounts' => false,
            'showIfEmpty' => false,
        ]);
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'categories' => $this->categories,
            'showIfEmpty' => $this->showIfEmpty,
            'showPostCounts' => $this->showPostCounts,
            'term' => $this->term,
        ];
    }

    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    public function setTerm(?Term $term): void
    {
        $this->term = $term;
    }
}
