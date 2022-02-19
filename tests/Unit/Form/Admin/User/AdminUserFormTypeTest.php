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

namespace NumberNine\Tests\Unit\Form\Admin\User;

use NumberNine\Bundle\Test\FormTestCase;
use NumberNine\Entity\User;
use NumberNine\Form\Admin\User\AdminUserFormType;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * @internal
 * @covers \NumberNine\Form\Admin\Term\AdminUserFormType
 */
final class AdminUserFormTypeTest extends FormTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->client->request('GET', '/');
        static::getContainer()->get('request_stack')->push($this->client->getRequest());
    }

    public function testSubmitValidData(): void
    {
        $adminRole = $this->userRoleRepository->findOneBy(['name' => 'Administrator']);

        $formData = [
            'username' => 'testuser',
            'email' => 'testuser@numberninecms.com',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'userRoles' => [$adminRole->getId()],
            'displayNameFormat' => 'username',
            'plainPassword' => 'password',
            '_token' => $this->csrfTokenManager->getToken('admin_user_form')->getValue(),
        ];

        $model = new User();

        $form = $this->factory->create(AdminUserFormType::class, $model);

        $expected = (new User())
            ->setUsername('testuser')
            ->setEmail('testuser@numberninecms.com')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->addUserRole($adminRole)
        ;

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertEquals($expected, $model);
        static::assertCount(0, iterator_to_array($form->getErrors(true), false));
    }

    public function testSubmitValidDataEdition(): void
    {
        $adminRole = $this->userRoleRepository->findOneBy(['name' => 'Administrator']);

        $formData = [
            'username' => 'testuser',
            'email' => 'testuser@numberninecms.com',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'userRoles' => [$adminRole->getId()],
            'displayNameFormat' => 'username',
            '_token' => $this->csrfTokenManager->getToken('admin_user_form')->getValue(),
        ];

        $model = new User();

        $form = $this->factory->create(AdminUserFormType::class, $model, ['mode' => 'edit']);

        $expected = (new User())
            ->setUsername('testuser')
            ->setEmail('testuser@numberninecms.com')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->addUserRole($adminRole)
        ;

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertEquals($expected, $model);
        static::assertCount(0, iterator_to_array($form->getErrors(true), false));
    }

    public function testSubmitInvalidData(): void
    {
        $formData = [
            'username' => '',
            'email' => 'invalid_email',
            'firstName' => '',
            'lastName' => '',
            'userRoles' => [],
            'displayNameFormat' => 'invalid',
            'plainPassword' => '',
            '_token' => '',
        ];

        $model = new User();

        $form = $this->factory->create(AdminUserFormType::class, $model);

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertGreaterThan(0, \count($form->getErrors()));
        static::assertGreaterThan(0, \count($form['username']->getErrors(true)));
        static::assertGreaterThan(0, \count($form['email']->getErrors(true)));
        static::assertGreaterThan(0, \count($form['plainPassword']->getErrors(true)));
        static::assertGreaterThan(0, \count($form['displayNameFormat']->getErrors(true)));
    }

    public function testSetDisplayNameFormatDefaultValues(): void
    {
        $model = new User();
        $form = $this->factory->create(AdminUserFormType::class, $model);

        $choices = $form->get('displayNameFormat')->getConfig()->getOption('choices');

        $expected = [
            '' => 'username',
            'First name' => 'first_only',
            'Last name' => 'last_only',
            'First name Last name' => 'first_last',
            'Last name First name' => 'last_first',
        ];

        static::assertEquals($expected, $choices);
    }

    public function testSetDisplayNameFormatExistingUser(): void
    {
        $model = (new User())
            ->setUsername('admin')
            ->setFirstName('John')
            ->setLastName('Doe')
        ;

        $form = $this->factory->create(AdminUserFormType::class, $model);

        $choices = $form->get('displayNameFormat')->getConfig()->getOption('choices');

        $expected = [
            'admin' => 'username',
            'John' => 'first_only',
            'Doe' => 'last_only',
            'John Doe' => 'first_last',
            'Doe John' => 'last_first',
        ];

        static::assertEquals($expected, $choices);
    }

    public function testProvideWrongModeFails(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->factory->create(AdminUserFormType::class, new User(), ['mode' => 'nonexistent']);
    }
}
