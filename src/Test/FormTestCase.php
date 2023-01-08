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

namespace NumberNine\Test;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

abstract class FormTestCase extends UserAwareTestCase
{
    protected FormFactoryInterface $factory;
    protected CsrfTokenManagerInterface $csrfTokenManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = $this->client->getContainer()->get('form.factory');
        $this->csrfTokenManager = $this->client->getContainer()->get('security.csrf.token_manager');
    }
}
