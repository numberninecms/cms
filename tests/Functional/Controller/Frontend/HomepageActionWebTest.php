<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\Functional\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
final class HomepageActionWebTest extends WebTestCase
{
    private KernelBrowser $client;

    public function testHomepageIsAccessible(): void
    {
        $this->client = static::createClient();
        $this->client->request('GET', '/');

        static::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
