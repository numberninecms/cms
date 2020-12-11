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
use NumberNine\Content\ShortcodeData;
use NumberNine\Entity\Post;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RecentPostsShortcodeData extends ShortcodeData
{
    /**
     * @Control\TextBox(label="Title")
     */
    protected string $title;

    /**
     * @Control\TextBox(label="Number of posts to show")
     */
    protected int $count;

    /** @var Post[] */
    protected array $posts;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'title' => 'Recent Posts',
            'count' => 10,
            'posts' => [],
        ]);
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'posts' => $this->posts,
        ];
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setPosts(array $posts): void
    {
        $this->posts = $posts;
    }
}
