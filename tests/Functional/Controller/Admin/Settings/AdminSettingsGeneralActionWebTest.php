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

namespace NumberNine\Tests\Functional\Controller\Admin\Settings;

use NumberNine\Tests\Functional\AdminTestCase;

final class AdminSettingsGeneralActionWebTest extends AdminTestCase
{
    public function testAdministratorCanAccessMediaLibrary(): void
    {
        $this->loginThenNavigateToAdminUrl(
            'Administrator',
            $this->urlGenerator->generate('numbernine_admin_settings_general')
        );

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('form[name="admin_settings_general_form"]');
    }
}