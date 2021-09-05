<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Social;

use InvalidArgumentException;

final class SocialSharer
{
    public function getFacebookShareLink(string $url): string
    {
        $this->validateUrl($url);

        return 'https://www.facebook.com/sharer.php?u=' . $url;
    }

    public function getTwitterShareLink(string $url): string
    {
        $this->validateUrl($url);

        return 'https://twitter.com/intent/tweet?url=' . $url;
    }

    public function getPinterestShareLink(string $url): string
    {
        $this->validateUrl($url);

        return 'http://pinterest.com/pin/create/button/?url=' . $url;
    }

    public function getLinkedInShareLink(string $url): string
    {
        $this->validateUrl($url);

        return 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url;
    }

    public function getEmailShareLink(string $subject, string $url): string
    {
        $this->validateUrl($url);

        return 'mailto:enteryour@addresshere.com?subject=' .
            rawurlencode($subject) . '&body=Check%20this%20out:%20' . $url;
    }

    /**
     * @return never
     */
    private function validateUrl(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException(sprintf('String "%s" must be a valid URL.', $url));
        }
    }
}
