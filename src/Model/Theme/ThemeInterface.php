<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Theme;

use NumberNine\Entity\ThemeOptions;

interface ThemeInterface
{
    public function getName(): string;

    public function setName(string $name): self;

    public function getSlug(): string;

    public function setSlug(string $slug): self;

    public function getParent(): ?self;

    public function setParent(self $parent): self;

    public function getThemeOptions(): ?ThemeOptions;

    public function setThemeOptions(ThemeOptions $options): self;

    public function getConfiguration(): array;

    public function setConfiguration(array $configuration): self;

    public function getNamespace(): string;

    public function getWebpackConfigName(): string;

    public function getRootPath(): string;

    public function getTranslationPath(): string;

    public function getTranslationDomain(): string;

    public function getTemplatePath(): string;

    public function getComponentPath(): string;

    public function getComponentNamespace(): string;

    public function getShortcodePath(): string;

    public function getShortcodeNamespace(): string;

    public function getFilePath(string $relativeFilename): string;
}
