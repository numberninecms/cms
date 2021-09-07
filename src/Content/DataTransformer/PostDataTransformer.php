<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Content\DataTransformer;

use Exception;
use NumberNine\Content\ShortcodeRenderer;
use NumberNine\Entity\Post;
use NumberNine\Http\RequestAnalyzer;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Model\DataTransformer\DataTransformerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class PostDataTransformer implements DataTransformerInterface
{
    private ?Request $request;

    public function __construct(
        private ShortcodeRenderer $shortcodeRenderer,
        private RequestAnalyzer $requestAnalyzer,
        RequestStack $requestStack
    ) {
        $this->request = $requestStack->getMainRequest();
    }

    public function supports($object): bool
    {
        return $object instanceof Post;
    }

    /**
     * @param Post $object
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function transform($object)
    {
        $this->transformTitle($object);
        $this->transformContent($object);

        return $object;
    }

    private function transformTitle(Post $post): void
    {
        if ($post->getStatus() === PublishingStatusInterface::STATUS_PRIVATE) {
            $newTitle = sprintf('Private: %s', $post->getTitle());
            $post->setTitle($newTitle);
        }
    }

    private function transformContent(Post $post): void
    {
        $isPreviewMode = $this->request
            && $this->requestAnalyzer->isPreviewMode()
            && $this->request->get('area') === null;

        $content = $isPreviewMode
            ? '<page-builder></page-builder>'
            : $this->shortcodeRenderer->applyShortcodes((string) $post->getContent());

        $post->setContent($content);
    }
}
