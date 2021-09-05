<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Ui\ContentEntity;

use NumberNine\Content\ContentService;
use NumberNine\Content\ShortcodeProcessor;
use NumberNine\Content\ShortcodeStore;
use NumberNine\Entity\ContentEntity;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[\Symfony\Component\Routing\Annotation\Route(path: '/{type}/{id<\d+>}/builder/', name: 'numbernine_admin_content_entity_page_builder_edit', methods: ['GET'], priority: '-1000')]
final class ContentEntityPageBuilderEditAction extends AbstractController implements AdminController
{
    public function __invoke(
        ContentService $contentService,
        ShortcodeStore $shortcodeStore,
        ShortcodeProcessor $shortcodeProcessor,
        ContentEntity $entity,
        string $type
    ): Response {
        $contentType = $contentService->getContentType($type);
        $shortcodes = [];

        foreach ($shortcodeStore->getShortcodes() as $name => $shortcode) {
            if (is_subclass_of($shortcode, EditableShortcodeInterface::class)) {
                $shortcodes[$name] = $shortcodeProcessor->shortcodeToArray($name);
            }
        }

        return $this->render('@NumberNine/admin/content_entity/builder_edit.html.twig', [
            'content_type' => $contentType,
            'type_slug' => $type,
            'entity' => $entity,
            'shortcodes' => $shortcodes,
        ]);
    }
}
