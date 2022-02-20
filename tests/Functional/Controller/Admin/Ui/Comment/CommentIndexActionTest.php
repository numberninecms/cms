<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\Functional\Controller\Admin\Ui\Comment;

use NumberNine\Bundle\Test\UserAwareTestCase;
use NumberNine\Security\Capabilities;

/**
 * @internal
 * @covers \NumberNine\Controller\Admin\Ui\Comment\CommentIndexAction
 */
final class CommentIndexActionTest extends UserAwareTestCase
{
    public function testUnauthorizedUserCantAccessPage(): void
    {
        $user = $this->createUser([Capabilities::ACCESS_ADMIN]);

        $this->loginThenNavigateToUrl($user, $this->urlGenerator->generate('numbernine_admin_comment_index'),);

        self::assertResponseRedirects($this->urlGenerator->generate('numbernine_login'));
    }

    public function testAuthorizedUserCanAccessPage(): void
    {
        $user = $this->createUser([Capabilities::ACCESS_ADMIN, Capabilities::MODERATE_COMMENTS]);

        $this->loginThenNavigateToUrl($user, $this->urlGenerator->generate('numbernine_admin_comment_index'),);

        self::assertResponseIsSuccessful();
    }
}
