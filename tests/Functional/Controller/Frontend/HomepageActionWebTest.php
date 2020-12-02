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

class HomepageActionWebTest extends DotEnvAwareWebTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testHomepageIsAccessible(): void
    {
        $this->client->request('GET', '/');

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
