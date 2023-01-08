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

namespace NumberNine\Tests\Unit\Form\Admin\UserRole;

use NumberNine\Entity\UserRole;
use NumberNine\Form\Admin\UserRole\AdminUserRoleFormType;
use NumberNine\Test\FormTestCase;

/**
 * @internal
 * @covers \NumberNine\Form\Admin\Term\AdminUserRoleFormType
 */
final class AdminUserRoleFormTypeTest extends FormTestCase
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
            'name' => 'GuestRole',
            '_token' => $this->csrfTokenManager->getToken('admin_user_role_form')->getValue(),
        ];

        $model = new UserRole();

        $form = $this->factory->create(AdminUserRoleFormType::class, $model);

        $expected = (new UserRole())
            ->setName('GuestRole')
        ;

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertEquals($expected, $model);
        static::assertCount(0, iterator_to_array($form->getErrors(true), false));
    }

    public function testSubmitInvalidData(): void
    {
        $formData = [
            'name' => '',
            '_token' => '',
        ];

        $model = new UserRole();

        $form = $this->factory->create(AdminUserRoleFormType::class, $model);

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertGreaterThan(0, \count($form->getErrors()));
        static::assertGreaterThan(0, \count($form['name']->getErrors(true)));
    }
}
