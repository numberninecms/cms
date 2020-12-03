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

use NumberNine\Tests\Functional\DotEnvAwareWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomepageActionWebTest extends DotEnvAwareWebTestCase
{
    public function testHomepageIsAccessible(): void
    {
        $this->client->request('GET', '/');

        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
