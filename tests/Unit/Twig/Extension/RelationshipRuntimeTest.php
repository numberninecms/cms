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

namespace NumberNine\Tests\Unit\Twig\Extension;

use NumberNine\Entity\Post;
use NumberNine\Tests\DotEnvAwareWebTestCase;
use NumberNine\Twig\Extension\RelationshipRuntime;

/**
 * @internal
 * @coversNothing
 */
final class RelationshipRuntimeTest extends DotEnvAwareWebTestCase
{
    private RelationshipRuntime $runtime;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client->request('GET', '/');
        $this->runtime = static::getContainer()->get(RelationshipRuntime::class);
    }

    public function testPostHasFeaturedImageRelationship(): void
    {
        static::assertTrue($this->runtime->hasRelationship(new Post(), 'featured_image'));
    }

    public function testInvalidRelationshipReturnsFalse(): void
    {
        static::assertFalse($this->runtime->hasRelationship(new Post(), 'invalid'));
    }
}
