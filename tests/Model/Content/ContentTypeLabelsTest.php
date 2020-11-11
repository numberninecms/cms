<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\Model\Content;

use NumberNine\Model\Content\ContentTypeLabels;
use PHPUnit\Framework\TestCase;

class ContentTypeLabelsTest extends TestCase
{
    public function testSingularName(): void
    {
        $labels = new ContentTypeLabels('media_file');
        self::assertEquals('media file', $labels->getSingularName());
    }

    public function testPluralName(): void
    {
        $labels = new ContentTypeLabels('media_file');
        self::assertEquals('media files', $labels->getPluralName());
    }
}
