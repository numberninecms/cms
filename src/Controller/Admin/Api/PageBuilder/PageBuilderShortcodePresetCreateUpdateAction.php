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

use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use NumberNine\Entity\Preset;
use NumberNine\Repository\PresetRepository;
use NumberNine\Content\ArrayToShortcodeConverter;
use NumberNine\Http\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "page_builder/shortcodes/{name}/presets/",
 *     name="numbernine_admin_page_builder_shortcode_post_presets",
 *     options={"expose"=true},
 *     methods={"POST"}
 * )
 */
final class PageBuilderShortcodePresetCreateUpdateAction
{
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        ResponseFactory $responseFactory,
        ArrayToShortcodeConverter $arrayToShortcodeConverter,
        PresetRepository $templateRepository,
        string $name
    ): JsonResponse {
        /** @var array $component */
        $component = $request->request->all();

        if (empty($component)) {
            throw new InvalidArgumentException('Submitted component is invalid.');
        }

        $shortcodeName = $component['name'];
        $content = $arrayToShortcodeConverter->convertMany([$component]);

        $preset = $templateRepository->findOneBy(['shortcodeName' => $shortcodeName, 'name' => $name]);

        if (!$preset) {
            $preset = (new Preset())
                ->setName($name)
                ->setShortcodeName($shortcodeName);
        }

        $preset->setContent($content);

        $entityManager->persist($preset);
        $entityManager->flush();

        return $responseFactory->createSuccessJsonResponse();
    }
}
