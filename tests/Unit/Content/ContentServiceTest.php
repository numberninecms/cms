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

use NumberNine\Content\ContentService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @coversNothing
 */
final class ContentServiceTest extends KernelTestCase
{
    private ContentService $contentService;

    protected function setUp(): void
    {
        $this->contentService = static::getContainer()->get(ContentService::class);
    }

    public function testGetTaxonomyDisplayName(): void
    {
        static::assertSame('category', $this->contentService->getTaxonomyDisplayName('category'));
        static::assertSame('categories', $this->contentService->getTaxonomyDisplayName('category', true));
    }
}
