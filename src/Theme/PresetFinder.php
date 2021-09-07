<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Theme;

use NumberNine\Model\Shortcode\ShortcodeInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
use Twig\Error\LoaderError;

final class PresetFinder implements PresetFinderInterface
{
    public function __construct(private Environment $twig, private TemplateResolver $templateResolver)
    {
    }

    public function findShortcodePresets(ShortcodeInterface $shortcode): array
    {
        $templates = $this->templateResolver->getShortcodeTemplatesCandidates($shortcode, 'html');
        $presetFiles = [];
        $presets = [];

        foreach ($templates as $template) {
            try {
                $directory = \dirname($this->twig->getLoader()->getSourceContext($template)->getPath());

                $finder = new Finder();
                $finder->in($directory)->files()->name('*.yaml');
                $presetFiles[] = array_keys(iterator_to_array($finder));
            } catch (LoaderError) {
                continue;
            }
        }

        $presetFiles = array_unique(array_merge([], ...$presetFiles));

        foreach ($presetFiles as $file) {
            $yaml = Yaml::parseFile($file);

            if ($this->validatePreset($yaml)) {
                $presets[$yaml['name']] = $yaml['content'];
            }
        }

        return $presets;
    }

    /**
     * @param mixed $preset
     */
    private function validatePreset($preset): bool
    {
        return \is_array($preset) && !empty($preset['name']) && !empty($preset['content']);
    }
}
