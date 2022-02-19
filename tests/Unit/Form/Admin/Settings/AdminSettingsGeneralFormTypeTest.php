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

namespace NumberNine\Tests\Unit\Form\Admin\Settings;

use NumberNine\Bundle\Test\FormTestCase;
use NumberNine\Entity\Post;
use NumberNine\Form\Admin\Settings\AdminSettingsGeneralFormType;
use NumberNine\Model\Content\PublishingStatusInterface;

/**
 * @internal
 * @covers \NumberNine\Form\Admin\Settings\AdminSettingsGeneralFormType
 */
final class AdminSettingsGeneralFormTypeTest extends FormTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->client->request('GET', '/');
        static::getContainer()->get('request_stack')->push($this->client->getRequest());
    }

    public function testSubmitValidData(): void
    {
        $pages = $this->createPages();

        $formData = [
            'site_title' => 'NumberNine CMS',
            'site_description' => 'Demo website',
            'blog_as_homepage' => true,
            'page_for_front' => $pages[0]->getId(),
            'page_for_posts' => $pages[1]->getId(),
            'page_for_my_account' => $pages[2]->getId(),
            'page_for_privacy' => $pages[3]->getId(),
            '_token' => $this->csrfTokenManager->getToken('admin_settings_general_form')->getValue(),
        ];

        $form = $this->factory->create(AdminSettingsGeneralFormType::class);

        $expected = [
            'site_title' => 'NumberNine CMS',
            'site_description' => 'Demo website',
            'blog_as_homepage' => true,
            'page_for_front' => $pages[0]->getId(),
            'page_for_posts' => $pages[1]->getId(),
            'page_for_my_account' => $pages[2]->getId(),
            'page_for_privacy' => $pages[3]->getId(),
        ];

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertEquals($expected, $form->getData());
        static::assertCount(0, $form->getErrors(true));
    }

    public function testSubmitInvalidData(): void
    {
        $formData = [
            'site_title' => '',
            'site_description' => '',
            'page_for_front' => 'invalid',
            'page_for_posts' => '',
            'page_for_my_account' => 13354544,
            '_token' => '',
        ];

        $form = $this->factory->create(AdminSettingsGeneralFormType::class);

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertGreaterThan(0, \count($form->getErrors()));
        static::assertGreaterThan(0, \count($form['site_title']->getErrors(true)));
        static::assertGreaterThan(0, \count($form['page_for_front']->getErrors(true)));
        static::assertGreaterThan(0, \count($form['page_for_my_account']->getErrors(true)));
    }

    public function testSubmitWithOptionalDataOmitted(): void
    {
        $pages = $this->createPages();

        $formData = [
            'site_title' => 'NumberNine CMS',
            'site_description' => 'Demo website',
            'blog_as_homepage' => true,
            'page_for_front' => $pages[0]->getId(),
            'page_for_posts' => $pages[1]->getId(),
            'page_for_my_account' => $pages[2]->getId(),
            'page_for_privacy' => null,
            '_token' => $this->csrfTokenManager->getToken('admin_settings_general_form')->getValue(),
        ];

        $form = $this->factory->create(AdminSettingsGeneralFormType::class);

        $expected = [
            'site_title' => 'NumberNine CMS',
            'site_description' => 'Demo website',
            'blog_as_homepage' => true,
            'page_for_front' => $pages[0]->getId(),
            'page_for_posts' => $pages[1]->getId(),
            'page_for_my_account' => $pages[2]->getId(),
            'page_for_privacy' => null,
        ];

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertEquals($expected, $form->getData());
        static::assertCount(0, $form->getErrors(true));
    }

    public function testPageChoices(): void
    {
        $pages = $this->createPages();
        $form = $this->factory->create(AdminSettingsGeneralFormType::class);
        $choices = $form['page_for_front']->getConfig()->getOption('choices');

        $expected = [
            $pages[0]->getTitle() => $pages[0]->getId(),
            $pages[1]->getTitle() => $pages[1]->getId(),
            $pages[2]->getTitle() => $pages[2]->getId(),
            $pages[3]->getTitle() => $pages[3]->getId(),
        ];

        static::assertEquals($expected, $choices);
    }

    private function createPages(): array
    {
        $admin = $this->createUser('Administrator');

        $page1 = (new Post())
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
            ->setCustomType('page')
            ->setTitle('Home')
            ->setAuthor($admin)
        ;

        $page2 = (new Post())
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
            ->setCustomType('page')
            ->setTitle('Blog')
            ->setAuthor($admin)
        ;

        $page3 = (new Post())
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
            ->setCustomType('page')
            ->setTitle('My account')
            ->setAuthor($admin)
        ;

        $page4 = (new Post())
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
            ->setCustomType('page')
            ->setTitle('Privacy')
            ->setAuthor($admin)
        ;

        $this->entityManager->persist($page1);
        $this->entityManager->persist($page2);
        $this->entityManager->persist($page3);
        $this->entityManager->persist($page4);
        $this->entityManager->flush();

        return [$page1, $page2, $page3, $page4];
    }
}
