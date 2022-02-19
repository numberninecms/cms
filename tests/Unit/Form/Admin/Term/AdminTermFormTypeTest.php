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

namespace NumberNine\Tests\Unit\Form\Admin\Term;

use NumberNine\Bundle\Test\FormTestCase;
use NumberNine\Entity\Term;
use NumberNine\Form\Admin\Term\AdminTermFormType;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * @internal
 * @covers \NumberNine\Form\Admin\Term\AdminTermFormType
 */
final class AdminTermFormTypeTest extends FormTestCase
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
            'name' => 'Blue',
            'slug' => 'blue',
            'description' => '',
            '_token' => $this->csrfTokenManager->getToken('admin_term_form')->getValue(),
        ];

        $model = new Term();

        $form = $this->factory->create(AdminTermFormType::class, $model);

        $expected = (new Term())
            ->setName('Blue')
            ->setSlug('blue')
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
            'slug' => '',
            'description' => '',
            '_token' => '',
        ];

        $model = new Term();

        $form = $this->factory->create(AdminTermFormType::class, $model);

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertGreaterThan(0, \count($form->getErrors()));
        static::assertGreaterThan(0, \count($form['name']->getErrors(true)));
        static::assertGreaterThan(0, \count($form['slug']->getErrors(true)));
    }

    public function testProvideWrongModeFails(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->factory->create(AdminTermFormType::class, new Term(), ['mode' => 'nonexistent']);
    }
}
