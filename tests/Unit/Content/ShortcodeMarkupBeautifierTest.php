<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\Unit\Content;

use NumberNine\Command\ThemeAwareCommandInterface;
use NumberNine\Content\ShortcodeMarkupBeautifier;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @internal
 * @coversNothing
 */
final class ShortcodeMarkupBeautifierTest extends WebTestCase implements ThemeAwareCommandInterface
{
    private const SAMPLE_SHORTCODE_DIRTY = '[section backgroundSize="original" margin="0px" padding="0px 0px 0px 0px"' .
        ' color="light"][section backgroundSize="original" backgroundColor="#46a6e9" margin="0px" padding="5px 30px' .
        ' 5px 30px" color="dark"][container justify="between" align="start" margin="0 auto"]Sample content' .
        ' [my_account_link loggedOutText="Login / Register" loggedInText="My account"][/container][/section]' .
        ' [container justify="center" align="center" margin="10px auto 10px auto"][link href="/" title="NumberNine' .
        ' - Every good business needs a good CMS software"][image fromTitle="NumberNine Logo" maxWidth="581"' .
        ' maxHeight="131" alt="NumberNine - Good CMS software"][/link][/container] [container justify="center"' .
        ' align="start" margin="0px auto 30px auto"][menu id="2"][/container][/section]';

    private const SAMPLE_SHORTCODE_EXPECTED = <<<'SHORTCODE'
[section backgroundSize="original" margin="0px" padding="0px 0px 0px 0px" color="light"]
    [section backgroundSize="original" backgroundColor="#46a6e9" margin="0px" padding="5px 30px 5px 30px" color="dark"]
        [container orientation="horizontal" justify="between" align="start" margin="0 auto"]
            Sample content
            [my_account_link loggedOutText="Login / Register" loggedInText="My account"]
        [/container]
    [/section]
    [container orientation="horizontal" justify="center" align="center" margin="10px auto 10px auto"]
        [link href="/" title="NumberNine - Every good business needs a good CMS software"]
            [image fromTitle="NumberNine Logo" maxWidth="581" maxHeight="131" alt="NumberNine - Good CMS software"]
        [/link]
    [/container]
    [container orientation="horizontal" justify="center" align="start" margin="0px auto 30px auto"]
        [menu style="main" id="2"]
    [/container]
[/section]
SHORTCODE;

    private KernelBrowser $client;
    private ?ShortcodeMarkupBeautifier $shortcodeMarkupBeautifier;
    private ?SerializerInterface $serializer;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->request('GET', '/');
        $this->shortcodeMarkupBeautifier = static::getContainer()->get(ShortcodeMarkupBeautifier::class);
        $this->serializer = static::getContainer()->get('serializer');
    }

    public function testShortcodeIsBeautified(): void
    {
        $beautified = $this->shortcodeMarkupBeautifier->beautify(self::SAMPLE_SHORTCODE_DIRTY);

        static::assertSame(self::SAMPLE_SHORTCODE_EXPECTED, $beautified);
    }
}
