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

namespace NumberNine\Tests\Functional\Controller\Admin\Ui\UserRole;

use NumberNine\Tests\UserAwareTestCase;

/**
 * @internal
 * @coversNothing
 */
final class UserRoleIndexActionTest extends UserAwareTestCase
{
    public function testAdministratorCanAccessMediaLibrary(): void
    {
        $this->loginThenNavigateToAdminUrl(
            'Administrator',
            $this->urlGenerator->generate('numbernine_admin_user_role_index')
        );

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('form[name="admin_user_role_index_form"]');
    }
}
