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
use NumberNine\Tests\DotEnvAwareWebTestCase;
use Symfony\Component\Serializer\SerializerInterface;

final class ShortcodeMarkupBeautifierTest extends DotEnvAwareWebTestCase implements ThemeAwareCommandInterface
{
    private const SAMPLE_SHORTCODE_DIRTY = '[section backgroundSize="original" margin="0px" padding="0px 0px 0px 0px"' .
        ' color="light"][section backgroundSize="original" backgroundColor="#46a6e9" margin="0px" padding="5px 30px' .
        ' 5px 30px" color="dark"][flex_row justify="between" align="start" margin="0 auto"]Sample content' .
        ' [my_account_link loggedOutText="Login / Register" loggedInText="My account"][/flex_row][/section][flex_row' .
        ' justify="center" align="center" margin="10px auto 10px auto"][link href="/" title="NumberNine - Every good' .
        ' business needs a good CMS software"][image fromTitle="NumberNine Logo" maxWidth="581" maxHeight="131"' .
        ' alt="NumberNine - Good CMS software"][/link][/flex_row] [flex_row justify="center" align="start"' .
        ' margin="0px auto 30px auto"][menu id="2"][/flex_row][/section]';

    private const SAMPLE_SHORTCODE_EXPECTED = <<<'SHORTCODE'
[section backgroundSize="original" margin="0px" padding="0px 0px 0px 0px" color="light"]
    [section backgroundSize="original" backgroundColor="#46a6e9" margin="0px" padding="5px 30px 5px 30px" color="dark"]
        [flex_row justify="between" align="start" margin="0 auto"]
            Sample content
            [my_account_link loggedOutText="Login / Register" loggedInText="My account"]
        [/flex_row]
    [/section]
    [flex_row justify="center" align="center" margin="10px auto 10px auto"]
        [link href="/" title="NumberNine - Every good business needs a good CMS software"]
            [image fromTitle="NumberNine Logo" maxWidth="581" maxHeight="131" alt="NumberNine - Good CMS software"]
        [/link]
    [/flex_row]
    [flex_row justify="center" align="start" margin="0px auto 30px auto"]
        [menu id="2"]
    [/flex_row]
[/section]
SHORTCODE;

    private ?ShortcodeMarkupBeautifier $shortcodeMarkupBeautifier;
    private ?SerializerInterface $serializer;

    public function setUp(): void
    {
        parent::setUp();
        $this->client->request('GET', '/');
        $this->shortcodeMarkupBeautifier = self::$container->get(ShortcodeMarkupBeautifier::class);
        $this->serializer = self::$container->get('serializer');
    }

    public function testShortcodeIsBeautified(): void
    {
        $beautified = $this->shortcodeMarkupBeautifier->beautify(self::SAMPLE_SHORTCODE_DIRTY);

        self::assertEquals(self::SAMPLE_SHORTCODE_EXPECTED, $beautified);
    }
}
