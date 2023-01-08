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

namespace NumberNine\Tests\Unit\Form\Admin\Menu;

use NumberNine\Entity\Menu;
use NumberNine\Form\Admin\Menu\AdminMenuFormType;
use NumberNine\Test\FormTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * @internal
 * @covers \NumberNine\Form\Admin\Menu\AdminMenuFormType
 */
final class AdminMenuFormTypeTest extends FormTestCase
{
    public function testSubmitValidDataForCreation(): void
    {
        $formData = [
            'name' => 'Main menu',
        ];

        $model = new Menu();

        $form = $this->factory->create(AdminMenuFormType::class, $model);

        $expected = (new Menu())
            ->setName('Main menu')
        ;

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertEquals($expected, $model);
    }

    public function testSubmitValidDataForEdition(): void
    {
        $formData = [
            'name' => 'Main menu',
            'menuItems' => '{"title":"Home","entityId":1,"children":[]}',
        ];

        $model = new Menu();

        $form = $this->factory->create(AdminMenuFormType::class, $model, ['mode' => 'edit']);

        $expected = (new Menu())
            ->setName('Main menu')
            ->setMenuItems([
                'title' => 'Home',
                'entityId' => 1,
                'children' => [],
            ])
        ;

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertEquals($expected, $model);
    }

    public function testProvideWrongModeFails(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->factory->create(AdminMenuFormType::class, new Menu(), ['mode' => 'nonexistent']);
    }
}
