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

namespace NumberNine\Tests\Unit\Shortcode;

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Entity\Term;
use NumberNine\Event\CurrentRequestTermEvent;
use NumberNine\Repository\TaxonomyRepository;
use NumberNine\Repository\TermRepository;
use NumberNine\Shortcode\CategoriesShortcode;
use NumberNine\Tests\ShortcodeTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class CategoriesShortcodeTest extends ShortcodeTestCase
{
    protected const SHORTCODE = CategoriesShortcode::class;
    private EntityManagerInterface $entityManager;
    private TaxonomyRepository $taxonomyRepository;
    private TermRepository $termRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function setUp(): void
    {
        parent::setUp();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->taxonomyRepository = static::getContainer()->get(TaxonomyRepository::class);
        $this->termRepository = static::getContainer()->get(TermRepository::class);
        $this->eventDispatcher = static::getContainer()->get(EventDispatcherInterface::class);
    }

    private function addFixtures(): void
    {
        $taxonomyCategory = $this->taxonomyRepository->findOneBy(['name' => 'category']);
        $taxonomyTag = $this->taxonomyRepository->findOneBy(['name' => 'tag']);

        $category1 = (new Term())
            ->setTaxonomy($taxonomyCategory)
            ->setName('Category1')
            ->setSlug('category1');
        $category2 = (new Term())
            ->setTaxonomy($taxonomyCategory)
            ->setName('Category2')
            ->setSlug('category2');
        $tag1 = (new Term())
            ->setTaxonomy($taxonomyTag)
            ->setName('Tag1')
            ->setSlug('tag1');

        $this->entityManager->persist($category1);
        $this->entityManager->persist($category2);
        $this->entityManager->persist($tag1);
        $this->entityManager->flush();
    }

    public function testShortcodeWithoutArguments(): void
    {
        $parameters = $this->processParameters([]);

        self::assertEquals([
            'title' => 'Categories',
            'categories' => [],
            'showIfEmpty' => false,
            'showPostCounts' => false,
            'term' => null,
        ], $parameters);
    }

    public function testSetTitle(): void
    {
        $parameters = $this->processParameters(['title' => 'My categories']);

        self::assertEquals([
            'title' => 'My categories',
            'categories' => [],
            'showIfEmpty' => false,
            'showPostCounts' => false,
            'term' => null,
        ], $parameters);
    }

    public function testSetShowIfEmpty(): void
    {
        $parameters = $this->processParameters(['showIfEmpty' => true]);

        self::assertEquals([
            'title' => 'Categories',
            'categories' => [],
            'showIfEmpty' => true,
            'showPostCounts' => false,
            'term' => null,
        ], $parameters);
    }

    public function testSetShowPostCounts(): void
    {
        $parameters = $this->processParameters(['showPostCounts' => true]);

        self::assertEquals([
            'title' => 'Categories',
            'categories' => [],
            'showIfEmpty' => false,
            'showPostCounts' => true,
            'term' => null,
        ], $parameters);
    }

    public function testCategoriesList(): void
    {
        $this->addFixtures();
        $categories = $this->termRepository->findByTaxonomyName('category', true);

        $parameters = $this->processParameters([]);

        self::assertEquals([
            'title' => 'Categories',
            'categories' => $categories,
            'showIfEmpty' => false,
            'showPostCounts' => false,
            'term' => null,
        ], $parameters);
    }

    public function testCategoriesListWithActiveTerm(): void
    {
        $this->addFixtures();
        $categories = $this->termRepository->findByTaxonomyName('category', true);

        $taxonomy = $this->taxonomyRepository->findOneBy(['name' => 'category']);
        $term = $this->termRepository->findOneBy(['taxonomy' => $taxonomy->getId(), 'name' => 'Category1']);

        $this->eventDispatcher->dispatch(new CurrentRequestTermEvent($term));

        $parameters = $this->processParameters([]);

        self::assertEquals([
            'title' => 'Categories',
            'categories' => $categories,
            'showIfEmpty' => false,
            'showPostCounts' => false,
            'term' => $term,
        ], $parameters);
    }
}
