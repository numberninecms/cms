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
use NumberNine\Model\DataTransformer\DataTransformerInterface;
use NumberNine\Http\RequestAnalyzer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class PostDataTransformer implements DataTransformerInterface
{
    private ShortcodeRenderer $shortcodeRenderer;
    private ?Request $request;
    private RequestAnalyzer $requestAnalyzer;

    public function __construct(
        ShortcodeRenderer $shortcodeRenderer,
        RequestAnalyzer $requestAnalyzer,
        RequestStack $requestStack
    ) {
        $this->shortcodeRenderer = $shortcodeRenderer;
        $this->request = $requestStack->getMasterRequest();
        $this->requestAnalyzer = $requestAnalyzer;
    }

    public function supports($object): bool
    {
        return $object instanceof Post;
    }

    /**
     * @param Post $object
     * @return mixed
     * @throws Exception
     */
    public function transform($object)
    {
        $isPreviewMode = $this->request
            && $this->requestAnalyzer->isPreviewMode()
            && $this->request->get('area') === null;

        $content = $isPreviewMode
            ? '<page-builder></page-builder>'
            : $this->shortcodeRenderer->applyShortcodes((string)$object->getContent());

        $object->setContent($content);

        return $object;
    }
}
