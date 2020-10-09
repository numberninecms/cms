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

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode;
use NumberNine\Annotation\Shortcode\Exclude;
use NumberNine\Entity\Post;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Repository\PostRepository;

/**
 * @Shortcode(name="recent_posts", label="Recent posts", description="Displays the most recent posts.", editable=true, icon="description")
 */
final class RecentPostsShortcode extends AbstractShortcode
{
    private PostRepository $postRepository;

    /**
     * @Control\TextBox(label="Title")
     */
    private string $title = 'Recent Posts';

    /**
     * @Control\TextBox(label="Number of posts to show")
     */
    private int $count = 10;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @return Post[]
     * @Exclude("serialization")
     */
    public function getPosts(): array
    {
        return $this->postRepository->getRecentPosts($this->count);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }
}
