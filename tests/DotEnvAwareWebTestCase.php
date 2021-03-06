<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Dotenv\Dotenv;

abstract class DotEnvAwareWebTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    public function setUp(): void
    {
        (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
        $this->client = static::createClient();
    }
}
