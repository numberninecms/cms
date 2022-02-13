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

use NumberNine\Form\Admin\Settings\AdminSettingsPermalinksFormType;
use NumberNine\Tests\FormTestCase;

/**
 * @internal
 * @covers \NumberNine\Form\Admin\Settings\AdminSettingsPermalinksFormType
 */
final class AdminSettingsPermalinksFormTypeTest extends FormTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->client->request('GET', '/');
        static::getContainer()->get('request_stack')->push($this->client->getRequest());
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'post' => '',
            'page' => '/valid/path/{slug}/',
            'block' => '/{year}/{month}/{day}/{slug}',
            '_token' => $this->csrfTokenManager->getToken('admin_settings_permalinks_form')->getValue(),
        ];

        $form = $this->factory->create(AdminSettingsPermalinksFormType::class);

        $expected = [
            'post' => null,
            'page' => '/valid/path/{slug}/',
            'block' => '/{year}/{month}/{day}/{slug}',
            'media_file' => null,
        ];

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertEquals($expected, $form->getData());
        static::assertCount(0, $form->getErrors(true));
    }

    public function testSubmitWithOptionalDataOmitted(): void
    {
        $formData = [
            'post' => null,
            'page' => '/valid/path/{slug}/',
            'block' => null,
            '_token' => $this->csrfTokenManager->getToken('admin_settings_permalinks_form')->getValue(),
        ];

        $form = $this->factory->create(AdminSettingsPermalinksFormType::class);

        $expected = [
            'post' => null,
            'page' => '/valid/path/{slug}/',
            'block' => null,
            'media_file' => null,
        ];

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertEquals($expected, $form->getData());
        static::assertCount(0, $form->getErrors(true));
    }

    public function testSubmitInvalidData(): void
    {
        $formData = [
            'post' => '/{year}/{month}/{day} {slug}',
            'page' => 'invalid path',
            'block' => 135,
        ];

        $form = $this->factory->create(AdminSettingsPermalinksFormType::class);

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertGreaterThan(0, \count($form->getErrors()));
        static::assertGreaterThan(0, \count($form['post']->getErrors(true)));
        static::assertGreaterThan(0, \count($form['page']->getErrors(true)));
        static::assertGreaterThan(0, \count($form['block']->getErrors(true)));
    }
}
