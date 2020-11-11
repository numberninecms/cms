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
use ReflectionClass;

abstract class AbstractTheme implements ThemeInterface
{
    private array $config = [];
    private string $name;
    private string $slug;
    private ?string $namespace = null;
    private ?ThemeInterface $parent = null;
    private ?ThemeOptions $options = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getParent(): ?ThemeInterface
    {
        return $this->parent;
    }

    public function setParent(ThemeInterface $parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    public function getThemeOptions(): ?ThemeOptions
    {
        return $this->options;
    }

    public function setThemeOptions(ThemeOptions $options): self
    {
        $this->options = $options;
        return $this;
    }

    public function getConfiguration(): array
    {
        return $this->config;
    }

    public function setConfiguration(array $configuration): self
    {
        $this->config = $configuration;
        return $this;
    }

    public function getNamespace(): string
    {
        if (!$this->namespace) {
            $reflection = new ReflectionClass(static::class);
            $this->namespace = $reflection->getNamespaceName();
        }

        return $this->namespace;
    }

    public function getWebpackConfigName(): string
    {
        return $this->getSlug();
    }

    final public function getRootPath(): string
    {
        $reflection = new ReflectionClass($this);
        return dirname((string)$reflection->getFileName()) . DIRECTORY_SEPARATOR;
    }

    public function getTranslationPath(): string
    {
        return $this->getRootPath() . 'Resources' . DIRECTORY_SEPARATOR . 'translations' . DIRECTORY_SEPARATOR;
    }

    public function getTranslationDomain(): string
    {
        return $this->getSlug();
    }

    public function getTemplatePath(): string
    {
        return $this->getRootPath() . 'Resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
    }

    final public function getFilePath(string $relativeFilename): string
    {
        return $this->getRootPath() . $relativeFilename;
    }

    public function getComponentPath(): string
    {
        return $this->getRootPath() . 'Component' . DIRECTORY_SEPARATOR;
    }

    public function getComponentNamespace(): string
    {
        return $this->getNamespace() . '\\' . trim(str_replace(
            $this->getRootPath(),
            '',
            $this->getComponentPath()
        ), '/');
    }

    public function getShortcodePath(): string
    {
        return $this->getRootPath() . 'Shortcode' . DIRECTORY_SEPARATOR;
    }
}
