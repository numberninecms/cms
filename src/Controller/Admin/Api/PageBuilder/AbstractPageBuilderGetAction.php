<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Api\PageBuilder;

use NumberNine\Content\ShortcodeProcessor;
use NumberNine\Content\ShortcodeRenderer;
use NumberNine\Content\ShortcodeStore;
use NumberNine\Http\ResponseFactory;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilder;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractPageBuilderGetAction extends AbstractController implements AdminController
{
    public function __construct(private ResponseFactory $responseFactory, private ShortcodeProcessor $shortcodeProcessor, private ShortcodeRenderer $shortcodeRenderer, private ShortcodeStore $shortcodeStore)
    {
    }

    protected function createPageBuilderResponseFromText(string $text): JsonResponse
    {
        $templates = [];
        $controls = [];
        $components = [];

        foreach ($this->shortcodeStore->getShortcodes() as $name => $shortcode) {
            $templates[$name] = $this->shortcodeRenderer->renderPageBuilderTemplate($shortcode);

            if (is_subclass_of($shortcode, EditableShortcodeInterface::class)) {
                $formBuilder = new PageBuilderFormBuilder();
                $shortcode->buildPageBuilderForm($formBuilder);
                $controls[$name] = $formBuilder->all();
                $components[$name] = $this->shortcodeProcessor->shortcodeToArray($name, [], 0);
            }
        }

        return $this->responseFactory->createSerializedJsonResponse(
            [
                'tree' => $this->shortcodeProcessor->buildShortcodeTree($text, true, false, true),
                'templates' => $templates,
                'controls' => $controls,
                'components' => $components,
            ]
        );
    }
}
