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

namespace NumberNine\Tests\Functional\Controller\Admin\Ui\Menu;

use NumberNine\Test\UserAwareTestCase;

/**
 * @internal
 * @coversNothing
 */
final class MenuCreateActionTest extends UserAwareTestCase
{
    public function testAdministratorCanAccessMenuCreate(): void
    {
        $this->loginThenNavigateToUrl(
            'Administrator',
            $this->urlGenerator->generate('numbernine_admin_menu_create')
        );

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('form[name="admin_menu_form"]');
    }
}
