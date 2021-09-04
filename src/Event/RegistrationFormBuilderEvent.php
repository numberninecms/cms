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

namespace NumberNine\Event;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class RegistrationFormBuilderEvent extends Event
{
    public function __construct(private FormBuilderInterface $builder)
    {
    }

    public function getBuilder(): FormBuilderInterface
    {
        return $this->builder;
    }
}
