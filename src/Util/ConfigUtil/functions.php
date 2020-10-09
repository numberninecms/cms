<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Util\ConfigUtil;

use function NumberNine\Util\StringUtil\human_readable_size_to_int;

/**
 * Gets the maximum size of uploadable files as defined in server's php configuration
 */
function get_file_upload_max_size(): int
{
    static $maxSize = -1;

    if ($maxSize < 0) {
        $postMaxSize = human_readable_size_to_int((string)ini_get('post_max_size'));
        $uploadMaxFileSize = human_readable_size_to_int((string)ini_get('upload_max_filesize'));

        if ($postMaxSize > 0) {
            $maxSize = $postMaxSize;
        }

        if ($uploadMaxFileSize > 0 && $uploadMaxFileSize < $maxSize) {
            $maxSize = $uploadMaxFileSize;
        }
    }

    return $maxSize;
}
