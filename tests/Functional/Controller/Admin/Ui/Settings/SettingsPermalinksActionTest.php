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

namespace NumberNine\Tests\Functional\Controller\Admin\Ui\Settings;

use NumberNine\Test\UserAwareTestCase;

/**
 * @internal
 * @coversNothing
 */
final class SettingsPermalinksActionTest extends UserAwareTestCase
{
    public function testAdministratorCanAccessSettingsPermalinks(): void
    {
        $this->loginThenNavigateToUrl(
            'Administrator',
            $this->urlGenerator->generate('numbernine_admin_settings_permalinks')
        );

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('form[name="admin_settings_permalinks_form"]');
    }
}
