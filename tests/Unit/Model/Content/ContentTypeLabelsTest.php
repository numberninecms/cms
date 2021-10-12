<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\Unit\Model\Content;

use NumberNine\Model\Content\ContentTypeLabels;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \NumberNine\Model\Content\ContentTypeLabels
 */
final class ContentTypeLabelsTest extends TestCase
{
    public function testSingularName(): void
    {
        $labels = new ContentTypeLabels('media_file');
        static::assertSame('media file', $labels->getSingularName());
    }

    public function testPluralName(): void
    {
        $labels = new ContentTypeLabels('media_file');
        static::assertSame('media files', $labels->getPluralName());
    }

    public function testAddNew(): void
    {
        $labels = new ContentTypeLabels('media_file');
        static::assertSame('Add new', $labels->getAddNew());
    }

    public function testAddNewItem(): void
    {
        $labels = new ContentTypeLabels('media_file');
        static::assertSame('Add new media file', $labels->getAddNewItem());
    }

    public function testEditItem(): void
    {
        $labels = new ContentTypeLabels('media_file');
        static::assertSame('Edit media file', $labels->getEditItem());
    }

    public function testMenuName(): void
    {
        $labels = new ContentTypeLabels('media_file');
        static::assertSame('Media files', $labels->getMenuName());
    }

    public function testNewItem(): void
    {
        $labels = new ContentTypeLabels('media_file');
        static::assertSame('New media file', $labels->getNewItem());
    }

    public function testSearchItems(): void
    {
        $labels = new ContentTypeLabels('media_file');
        static::assertSame('Search media files', $labels->getSearchItems());
    }

    public function testNotFound(): void
    {
        $labels = new ContentTypeLabels('media_file');
        static::assertSame('No media files found', $labels->getNotFound());
    }

    public function testNotFoundInTrash(): void
    {
        $labels = new ContentTypeLabels('media_file');
        static::assertSame('No media files found in trash', $labels->getNotFoundInTrash());
    }

    public function testAllItems(): void
    {
        $labels = new ContentTypeLabels('media_file');
        static::assertSame('All media files', $labels->getAllItems());
    }

    public function testArchives(): void
    {
        $labels = new ContentTypeLabels('media_file');
        static::assertSame('Media files archives', $labels->getArchives());
    }

    public function testViewItem(): void
    {
        $labels = new ContentTypeLabels('media_file');
        static::assertSame('View media file', $labels->getViewItem());
    }

    public function testViewItems(): void
    {
        $labels = new ContentTypeLabels('media_file');
        static::assertSame('View media files', $labels->getViewItems());
    }
}
