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

namespace NumberNine\Tests\Functional\Controller\Admin\Ui\User;

use NumberNine\Tests\Functional\AdminTestCase;

final class UserIndexActionTest extends AdminTestCase
{
    public function testAdministratorCanAccessMediaLibrary(): void
    {
        $this->loginThenNavigateToAdminUrl(
            'Administrator',
            $this->urlGenerator->generate('numbernine_admin_user_index')
        );

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('turbo-frame#numbernine_admin_user_index');
    }
}
