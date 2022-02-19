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
use NumberNine\Form\Admin\Settings\AdminSettingsEmailsFormType;

/**
 * @internal
 * @covers \NumberNine\Form\Admin\Settings\AdminSettingsEmailsFormType
 */
final class AdminSettingsEmailsFormTypeTest extends FormTestCase
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
            'mailer_sender_name' => 'Test form',
            'mailer_sender_address' => 'test@numberninecms.com',
            '_token' => $this->csrfTokenManager->getToken('admin_settings_emails_form')->getValue(),
        ];

        $form = $this->factory->create(AdminSettingsEmailsFormType::class);

        $expected = [
            'mailer_sender_name' => 'Test form',
            'mailer_sender_address' => 'test@numberninecms.com',
        ];

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertEquals($expected, $form->getData());
        static::assertCount(0, $form->getErrors(true));
    }

    public function testSubmitInvalidData(): void
    {
        $formData = [
            'mailer_sender_name' => '',
            'mailer_sender_address' => 'invalid_email',
        ];

        $form = $this->factory->create(AdminSettingsEmailsFormType::class);

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertGreaterThan(0, \count($form->getErrors()));
        static::assertGreaterThan(0, \count($form['mailer_sender_name']->getErrors(true)));
        static::assertGreaterThan(0, \count($form['mailer_sender_address']->getErrors(true)));
    }
}
