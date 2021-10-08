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

namespace NumberNine\Tests\Unit\Form\Admin\Content;

use NumberNine\Entity\Post;
use NumberNine\Form\Admin\Content\AdminContentEntityEditFormType;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Tests\FormTestCase;

/**
 * @internal
 * @covers \NumberNine\Form\Admin\Content\AdminContentEntityEditFormType
 */
final class AdminContentEntityEditFormTypeTest extends FormTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->client->request('GET', '/');
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'title' => 'Homepage',
            'slug' => 'homepage',
            'status' => PublishingStatusInterface::STATUS_DRAFT,
            'customFields' => [
                ['key' => 'field1', 'value' => 'Test field'],
                ['key' => 'field2', 'value' => 'Another test field'],
            ],
            'seoTitle' => 'SEO Title',
            'seoDescription' => 'SEO Description',
            '_token' => $this->csrfTokenManager->getToken('admin_content_entity_edit_form')->getValue(),
        ];

        $model = (new Post())
            ->setCustomType('post')
        ;

        $form = $this->factory->create(AdminContentEntityEditFormType::class, $model, [
            'editor_extensions' => [],
        ]);

        $expected = (new Post())
            ->setCustomType('post')
            ->setTitle('Homepage')
            ->setSlug('homepage')
            ->setStatus(PublishingStatusInterface::STATUS_DRAFT)
            ->setCustomFields([
                'field1' => 'Test field',
                'field2' => 'Another test field',
                'page_template' => null,
            ])
            ->setSeoTitle('SEO Title')
            ->setSeoDescription('SEO Description')
        ;

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertEquals($expected, $model);
        static::assertCount(0, $form->getErrors(true));
    }

    public function testSubmitInvalidData(): void
    {
        $formData = [
            'title' => '',
            'slug' => '',
            'status' => 'invalid_status',
            'customFields' => [
                'field1' => 'Test field',
                'field2' => 'Another test field',
            ],
            '_token' => '',
        ];

        $model = (new Post())
            ->setCustomType('post')
        ;

        $form = $this->factory->create(AdminContentEntityEditFormType::class, $model, [
            'editor_extensions' => [],
        ]);

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertGreaterThan(0, \count($form->getErrors()));
        static::assertGreaterThan(0, \count($form['title']->getErrors(true)));
        static::assertGreaterThan(0, \count($form['status']->getErrors(true)));
    }
}
