<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\Unit\Social;

use InvalidArgumentException;
use NumberNine\Social\SocialSharer;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class SocialSharerTest extends TestCase
{
    private SocialSharer $socialSharer;

    protected function setUp(): void
    {
        $this->socialSharer = new SocialSharer();
    }

    public function testFacebookShareLink(): void
    {
        static::assertSame(
            'https://www.facebook.com/sharer.php?u=https://www.google.com',
            $this->socialSharer->getFacebookShareLink('https://www.google.com')
        );

        $this->expectException(InvalidArgumentException::class);
        $this->socialSharer->getFacebookShareLink('Not a valid url');
    }

    public function testTwitterShareLink(): void
    {
        static::assertSame(
            'https://twitter.com/intent/tweet?url=https://www.google.com',
            $this->socialSharer->getTwitterShareLink('https://www.google.com')
        );

        $this->expectException(InvalidArgumentException::class);
        $this->socialSharer->getTwitterShareLink('Not a valid url');
    }

    public function testPinterestShareLink(): void
    {
        static::assertSame(
            'http://pinterest.com/pin/create/button/?url=https://www.google.com',
            $this->socialSharer->getPinterestShareLink('https://www.google.com')
        );

        $this->expectException(InvalidArgumentException::class);
        $this->socialSharer->getPinterestShareLink('Not a valid url');
    }

    public function testLinkedInShareLink(): void
    {
        static::assertSame(
            'https://www.linkedin.com/shareArticle?mini=true&url=https://www.google.com',
            $this->socialSharer->getLinkedInShareLink('https://www.google.com')
        );

        $this->expectException(InvalidArgumentException::class);
        $this->socialSharer->getLinkedInShareLink('Not a valid url');
    }

    public function testEmailShareLink(): void
    {
        static::assertSame(
            'mailto:enteryour@addresshere.com?subject=Some%20subject%20line&body=Check%20this%20out:%20' .
            'https://www.google.com',
            $this->socialSharer->getEmailShareLink('Some subject line', 'https://www.google.com')
        );

        $this->expectException(InvalidArgumentException::class);
        $this->socialSharer->getEmailShareLink('Some subject line', 'Not a valid url');
    }
}
