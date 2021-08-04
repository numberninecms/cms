<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="NumberNine\Repository\ThemeOptionsRepository")
 * @ORM\Table(name="themeoptions")
 */
class ThemeOptions
{
    public const MENU_LOCATIONS = 'menu_locations';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private ?string $theme;

    /**
     * @ORM\Column(type="json")
     */
    private array $options = [];

    /**
     * @ORM\Column(type="json")
     */
    private array $draftOptions = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getDraftOptions(): ?array
    {
        return $this->draftOptions;
    }

    public function setDraftOptions(array $draftOptions): self
    {
        $this->draftOptions = $draftOptions;

        return $this;
    }

    public function getMergedOptions(): array
    {
        return array_merge($this->options, $this->draftOptions);
    }

    public function merge(): void
    {
        $this->options = $this->getMergedOptions();
        $this->draftOptions = [];
    }
}
