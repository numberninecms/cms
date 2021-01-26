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

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Entity\ThemeOptions;
use NumberNine\Model\Theme\ThemeInterface;
use NumberNine\Repository\ThemeOptionsRepository;

final class ThemeOptionsReadWriter
{
    private EntityManagerInterface $entityManager;
    private ThemeOptionsRepository $themeOptionsRepository;

    public function __construct(EntityManagerInterface $entityManager, ThemeOptionsRepository $themeOptionsRepository)
    {
        $this->entityManager = $entityManager;
        $this->themeOptionsRepository = $themeOptionsRepository;
    }

    public function readAll(ThemeInterface $theme, bool $draft = false, bool $merged = false): array
    {
        $themeOptions = $this->themeOptionsRepository->findOneBy(['theme' => $theme->getName()]);

        if (!$themeOptions instanceof ThemeOptions) {
            $themeOptions = (new ThemeOptions())->setTheme((string)$theme->getName());
        }

        $theme->setThemeOptions($themeOptions);

        if ($merged) {
            return $themeOptions->getMergedOptions();
        }

        if ($draft) {
            return $themeOptions->getDraftOptions() ?? [];
        }

        return $themeOptions->getOptions() ?? [];
    }

    public function writeAll(ThemeInterface $theme, array $options, bool $draft = false, bool $flush = true): void
    {
        $themeOptions = $this->themeOptionsRepository->findOneBy(['theme' => $theme->getName()]);

        if (!$themeOptions instanceof ThemeOptions) {
            $themeOptions = (new ThemeOptions())->setTheme((string)$theme->getName());
        }

        if (!$draft) {
            $themeOptions->setOptions($options);
        } else {
            $themeOptions->setDraftOptions($options);
        }

        $theme->setThemeOptions($themeOptions);
        $this->entityManager->persist($themeOptions);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param ThemeInterface $theme
     * @param string $optionName
     * @param mixed $default
     * @param bool $draft
     * @param bool $merged
     * @return mixed
     */
    public function read(
        ThemeInterface $theme,
        string $optionName,
        $default = null,
        bool $draft = false,
        bool $merged = false
    ) {
        return $this->readAll($theme, $draft, $merged)[$optionName] ?? $default;
    }

    /**
     * @param ThemeInterface $theme
     * @param string $option
     * @param mixed $value
     * @param bool $draft
     * @param bool $flush
     * @param bool $overwrite
     */
    public function write(
        ThemeInterface $theme,
        string $option,
        $value,
        bool $draft = false,
        bool $flush = false,
        bool $overwrite = true
    ): void {
        $options = $this->readAll($theme, $draft);

        if ($overwrite || !array_key_exists($option, $options)) {
            $options[$option] = $value;
            $this->writeAll($theme, $options, $draft, $flush);
        }
    }
}
