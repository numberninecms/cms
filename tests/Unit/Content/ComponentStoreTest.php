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

namespace NumberNine\Tests\Unit\Content;

use NumberNine\Bundle\Test\DotEnvAwareWebTestCase;
use NumberNine\Component\Content\Comments\Comments;
use NumberNine\Content\ComponentStore;

/**
 * @internal
 * @coversNothing
 */
final class ComponentStoreTest extends DotEnvAwareWebTestCase
{
    private ComponentStore $componentStore;
    private Comments $commentsComponent;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client->request('GET', '/');
        $this->componentStore = static::getContainer()->get(ComponentStore::class);
        $this->commentsComponent = static::getContainer()->get(Comments::class);
    }

    public function testCoreComponentIsResolved(): void
    {
        static::assertSame($this->commentsComponent, $this->componentStore->getComponent('Content/Comments'));
    }

    public function testUnknownComponentDoesNotOverridesCoreComponent(): void
    {
        $unknownComponent = new \NumberNine\Tests\Dummy\Component\Content\Comments\Comments();
        $this->componentStore->addComponent($unknownComponent);
        static::assertSame($this->commentsComponent, $this->componentStore->getComponent('Content/Comments'));
    }

    public function testThemeComponentOverridesCoreComponent(): void
    {
        $themeComponent = new \NumberNine\ChapterOne\Component\Content\Comments\Comments();
        $this->componentStore->addComponent($themeComponent);
        static::assertSame($themeComponent, $this->componentStore->getComponent('Content/Comments'));
    }

    public function testAppComponentOverridesCoreComponent(): void
    {
        $appComponent = new \App\Component\Content\Comments\Comments();
        $this->componentStore->addComponent($appComponent);
        static::assertSame($appComponent, $this->componentStore->getComponent('Content/Comments'));
    }

    public function testAppComponentOverridesCoreAndThemeComponents(): void
    {
        $themeComponent = new \NumberNine\ChapterOne\Component\Content\Comments\Comments();
        $appComponent = new \App\Component\Content\Comments\Comments();
        $this->componentStore->addComponent($appComponent);
        $this->componentStore->addComponent($themeComponent);
        static::assertSame($appComponent, $this->componentStore->getComponent('Content/Comments'));
    }

    public function testAppComponentOverridesCoreAndThemeComponentsInverseOrder(): void
    {
        $themeComponent = new \NumberNine\ChapterOne\Component\Content\Comments\Comments();
        $appComponent = new \App\Component\Content\Comments\Comments();
        $this->componentStore->addComponent($themeComponent);
        $this->componentStore->addComponent($appComponent);
        static::assertSame($appComponent, $this->componentStore->getComponent('Content/Comments'));
    }
}
