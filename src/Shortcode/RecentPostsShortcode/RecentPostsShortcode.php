<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\RecentPostsShortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Annotation\Shortcode\Exclude;
use NumberNine\Entity\Post;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Repository\PostRepository;

/**
 * @Shortcode(
 *     name="recent_posts",
 *     label="Recent posts",
 *     description="Displays the most recent posts.",
 *     editable=true,
 *     icon="description"
 * )
 */
final class RecentPostsShortcode extends AbstractShortcode
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @return Post[]
     * @Exclude("serialization")
     */
    private function getPosts(int $count): array
    {
        return $this->postRepository->getRecentPosts($count);
    }

    /**
     * @param RecentPostsShortcodeData $data
     */
    public function process($data): void
    {
        $data->setPosts($this->getPosts($data->getCount()));
    }
}
