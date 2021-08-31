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

namespace NumberNine\Tests\Unit\Twig\Extension;

use NumberNine\Configuration\ConfigurationReadWriter;
use NumberNine\Entity\ContentEntityTerm;
use NumberNine\Entity\Post;
use NumberNine\Entity\Term;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Model\General\Settings;
use NumberNine\Repository\TaxonomyRepository;
use NumberNine\Tests\UserAwareTestCase;
use NumberNine\Twig\Extension\ThemeRuntime;

final class ThemeRuntimeTest extends UserAwareTestCase
{
    private ThemeRuntime $runtime;
    private TaxonomyRepository $taxonomyRepository;
    private ConfigurationReadWriter $configurationReadWriter;
    private Post $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client->request('GET', '/');
        $this->runtime = static::getContainer()->get(ThemeRuntime::class);
        $this->taxonomyRepository = static::getContainer()->get(TaxonomyRepository::class);
        $this->configurationReadWriter = static::getContainer()->get(ConfigurationReadWriter::class);
        $this->post = $this->getPost();
    }

    private function getPost(): Post
    {
        $author = $this->userFactory->createUser(
            'contributor',
            'contributor@numbernine-fakedomain.com',
            'password',
            [$this->userRoleRepository->findOneBy(['name' => 'Contributor'])],
        );

        $taxonomy = $this->taxonomyRepository->findOneBy(['name' => 'category']);
        $term1 = (new Term())->setName('Animals')->setTaxonomy($taxonomy);
        $term2 = (new Term())->setName('Colors')->setTaxonomy($taxonomy);

        $post = (new Post())
            ->setTitle('My blog post')
            ->setCustomType('post')
            ->setAuthor($author)
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
            ->setCreatedAt(new \DateTime('2013/12/18'))
            ->setPublishedAt(new \DateTime('2015/05/13'))
        ;

        $cet1 = (new ContentEntityTerm())->setContentEntity($post)->setTerm($term1);
        $cet2 = (new ContentEntityTerm())->setContentEntity($post)->setTerm($term2);

        $post
            ->addContentEntityTerm($cet1)
            ->addContentEntityTerm($cet2);

        $this->entityManager->persist($term1);
        $this->entityManager->persist($term2);
        $this->entityManager->persist($cet1);
        $this->entityManager->persist($cet2);
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $post;
    }

    public function testGetCurrentTheme(): void
    {
        self::assertEquals('ChapterOne', $this->runtime->getCurrentTheme()->getName());
    }

    public function testRenderExistingArea(): void
    {
        self::assertStringContainsString('<section', $this->runtime->renderArea('header'));
    }

    public function testRenderNonExistingArea(): void
    {
        self::assertEquals('', $this->runtime->renderArea('nonexistent'));
    }

    public function testRenderComponent(): void
    {
        self::assertStringContainsString(
            '<h1 class="post-title">My blog post</h1>',
            $this->runtime->renderComponent('Post/SinglePost', [
                'post' => $this->post,
            ]),
        );
    }

    public function testRenderNonExistentComponent(): void
    {
        self::assertEquals('', $this->runtime->renderComponent('NonExistentComponent'));
    }

    public function testRenderNonExistentComponentAsAdministrator(): void
    {
        $this->loginAs('Administrator');
        self::assertStringContainsString(
            'Component "NonExistentComponent" is missing',
            $this->runtime->renderComponent('NonExistentComponent'),
        );
    }

    public function testGetThemeOption(): void
    {
        self::assertIsArray($this->runtime->getThemeOption('areas'));
    }

    public function testGetThemeNonExistentOption(): void
    {
        self::assertNull($this->runtime->getThemeOption('nonexistent'));
    }

    public function testGetSetting(): void
    {
        self::assertEquals('ChapterOne', $this->runtime->getSetting('active_theme'));
    }

    public function testGetNonExistentSetting(): void
    {
        self::assertNull($this->runtime->getSetting('nonexistent'));
    }

    public function testGetNonExistentSettingWithDefault(): void
    {
        self::assertEquals('Default value', $this->runtime->getSetting('nonexistent', 'Default value'));
    }

    public function testGetEntityUrl(): void
    {
        self::assertEquals('/2015/05/13/my-blog-post', $this->runtime->getEntityUrl($this->post));
    }

    public function testGetEntityLink(): void
    {
        self::assertEquals(
            '<a href="/2015/05/13/my-blog-post">My blog post</a>',
            $this->runtime->getEntityLink($this->post),
        );
    }

    public function testGetEntityLinkWithAttributes(): void
    {
        self::assertEquals(
            '<a href="/2015/05/13/my-blog-post" data-random="12">My blog post</a>',
            $this->runtime->getEntityLink($this->post, ['data-random' => 12]),
        );
    }

    public function testGetEntityLinkWithText(): void
    {
        self::assertEquals(
            '<a href="/2015/05/13/my-blog-post">Some text</a>',
            $this->runtime->getEntityLink($this->post, [], 'Some text'),
        );
    }

    public function testGetEntityAdminUrl(): void
    {
        self::assertEquals(
            sprintf('/admin/posts/%d/', $this->post->getId()),
            $this->runtime->getEntityAdminUrl($this->post),
        );
    }

    public function testGetTermsLinkList(): void
    {
        self::assertEquals(
            '<a href="/category/animals/">Animals</a>, <a href="/category/colors/">Colors</a>',
            $this->runtime->getTermsLinkList($this->post, 'category'),
        );
    }

    public function testGetTermLink(): void
    {
        self::assertEquals(
            '<a href="/category/animals/">Animals</a>',
            $this->runtime->getTermLink($this->post->getTerms('category')[0]),
        );
    }

    public function testGetBaseTemplate(): void
    {
        self::assertEquals('@ChapterOne/base.html.twig', $this->runtime->getBaseTemplate());
    }

    public function testGetPath(): void
    {
        $myAccount = (new Post())
            ->setCustomType('page')
            ->setTitle('My account')
        ;

        $this->entityManager->persist($myAccount);
        $this->entityManager->flush();

        $this->configurationReadWriter->write(Settings::PAGE_FOR_MY_ACCOUNT, $myAccount->getId());

        self::assertEquals('/my-account', $this->runtime->getPath(Settings::PAGE_FOR_MY_ACCOUNT));
        self::assertEquals('/my-account/page/2/', $this->runtime->getPath(Settings::PAGE_FOR_MY_ACCOUNT, 2));
        self::assertEquals(
            'http://localhost/my-account',
            $this->runtime->getPath(Settings::PAGE_FOR_MY_ACCOUNT, 1, true),
        );
    }

    public function testGetCurrentRoutePagePath(): void
    {
        self::assertEquals('/page/4/', $this->runtime->getCurrentRoutePagePath(4));
    }
}
