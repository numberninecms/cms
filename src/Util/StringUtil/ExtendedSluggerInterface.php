<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Util\StringUtil;

use Symfony\Component\String\Slugger\SluggerInterface;

interface ExtendedSluggerInterface extends SluggerInterface
{
    public function getUniqueFilenameSlug(string $directory, string $filename): string;
}
