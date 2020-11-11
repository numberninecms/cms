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

use NumberNine\Model\Admin\AdminController;
use NumberNine\Content\ShortcodeProcessor;
use NumberNine\Form\FormGenerator;
use NumberNine\Http\ResponseFactory;
use NumberNine\Content\ShortcodeStore;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractPageBuilderGetAction implements AdminController
{
    private ResponseFactory $responseFactory;
    private ShortcodeProcessor $shortcodeProcessor;
    private ShortcodeStore $shortcodeStore;
    private FormGenerator $formGenerator;

    public function __construct(
        ResponseFactory $responseFactory,
        ShortcodeProcessor $shortcodeProcessor,
        ShortcodeStore $shortcodeStore,
        FormGenerator $formGenerator
    ) {
        $this->responseFactory = $responseFactory;
        $this->shortcodeProcessor = $shortcodeProcessor;
        $this->shortcodeStore = $shortcodeStore;
        $this->formGenerator = $formGenerator;
    }

    protected function createPageBuilderResponseFromText(string $text): JsonResponse
    {
        $templates = [];
        $controls = [];
        $components = [];

        foreach ($this->shortcodeStore->getShortcodes() as $name => $shortcode) {
            $templates[$name] = $shortcode->renderPageBuilderTemplate();
            $controls[$name] = $this->formGenerator->getFormControls($shortcode);
            $metadata = $this->shortcodeStore->getShortcodeMetadata($name);

            if ($metadata->editable === true) {
                $components[$name] = $this->shortcodeProcessor->shortcodeToArray($name, null, 0, true);
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
