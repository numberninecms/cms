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
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use NumberNine\Repository\PostRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @Shortcode(
 *     name="recent_posts",
 *     label="Recent posts",
 *     description="Displays the most recent posts.",
 *     icon="description"
 * )
 */
final class RecentPostsShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('title')
            ->add('count', null, ['label' => 'Number of posts to show'])
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'title' => 'Recent Posts',
            'count' => 10,
            'posts' => [],
        ]);
    }

    public function processParameters(array $parameters): array
    {
        return [
            'title' => $parameters['title'],
            'posts' => $this->getPosts($parameters['count']),
        ];
    }

    private function getPosts(int $count): array
    {
        return $this->postRepository->getRecentPosts($count);
    }
}
