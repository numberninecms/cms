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

namespace NumberNine\Tests\Functional\Controller\Frontend\Term;

use NumberNine\Bundle\Test\UserAwareTestCase;
use NumberNine\Entity\ContentEntityTerm;
use NumberNine\Entity\Post;
use NumberNine\Entity\Term;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Repository\TaxonomyRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \NumberNine\Controller\Frontend\Term\IndexAction
 */
final class IndexActionTest extends UserAwareTestCase
{
    private TaxonomyRepository $taxonomyRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taxonomyRepository = static::getContainer()->get(TaxonomyRepository::class);
    }

    public function testTermIndexPageWorks(): void
    {
        $posts = $this->createPosts();
        $this->client->request('GET', '/category/travel/');

        static::assertResponseIsSuccessful();
        static::assertSelectorTextContains('main', $posts[1]->getTitle());
        static::assertSelectorTextNotContains('main', $posts[0]->getTitle());
    }

    public function testTermIndexPageFailsWithWrongTerm(): void
    {
        $this->createPosts();
        $this->client->request('GET', '/category/test/');

        static::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    private function createPosts(): array
    {
        $user = $this->createUser('Contributor');

        $taxonomy = $this->taxonomyRepository->findOneBy(['name' => 'category']);
        $term1 = (new Term())->setName('Art')->setTaxonomy($taxonomy);
        $term2 = (new Term())->setName('Travel')->setTaxonomy($taxonomy);

        $post1 = (new Post())
            ->setCustomType('post')
            ->setAuthor($user)
            ->setTitle('Art post')
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
        ;

        $cet1 = (new ContentEntityTerm())->setContentEntity($post1)->setTerm($term1);
        $post1->addContentEntityTerm($cet1);

        $post2 = (new Post())
            ->setCustomType('post')
            ->setAuthor($user)
            ->setTitle('Travel post')
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
        ;

        $cet2 = (new ContentEntityTerm())->setContentEntity($post2)->setTerm($term2);
        $post2->addContentEntityTerm($cet2);

        $this->entityManager->persist($term1);
        $this->entityManager->persist($term2);
        $this->entityManager->persist($cet1);
        $this->entityManager->persist($cet2);
        $this->entityManager->persist($post1);
        $this->entityManager->persist($post2);
        $this->entityManager->flush();

        return [$post1, $post2];
    }
}
