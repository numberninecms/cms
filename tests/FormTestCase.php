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

namespace NumberNine\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormFactoryInterface;

abstract class FormTestCase extends KernelTestCase
{
    protected FormFactoryInterface $factory;

    public function setUp(): void
    {
        /** @var FormFactoryInterface $factory */
        $factory = static::getContainer()->get('form.factory');
        $this->factory = $factory;
    }
}
