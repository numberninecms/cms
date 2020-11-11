<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Shortcode;

use NumberNine\Annotation\Shortcode\Exclude;
use NumberNine\Model\Component\Features\OptionsAndSettingsInjectionTrait;
use NumberNine\Model\Component\Features\RenderTrait;
use NumberNine\Model\Reflection\BatchSetPropertiesTrait;
use ReflectionException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractShortcode implements ShortcodeInterface
{
    use RenderTrait;
    use OptionsAndSettingsInjectionTrait;
    use BatchSetPropertiesTrait;

    private ?string $content = null;
    private array $unknownParameters = [];

    /**
     * @param bool $isSerialization
     * @return array
     * @throws ReflectionException
     * @Exclude
     */
    public function getParameters(bool $isSerialization = false): array
    {
        return $this->getValues($isSerialization);
    }

    /**
     * @param array $parameters
     * @param bool $isSerialization
     * @return ShortcodeInterface
     */
    public function setParameters(array $parameters, bool $isSerialization = false): ShortcodeInterface
    {
        $this->batchSetProperties($parameters, $isSerialization);
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): ShortcodeInterface
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @Exclude
     */
    public function getUnknownParameters(): array
    {
        return $this->unknownParameters;
    }

    /**
     * @param string $parameter
     * @param mixed $value
     */
    public function addUnknownParameter(string $parameter, $value): void
    {
        $this->unknownParameters[$parameter] = $value;
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws ReflectionException
     */
    final public function renderPageBuilderTemplate(): string
    {
        return trim($this->getTwig()->render(
            $this->templateResolver->resolveShortcodePageBuilder($this),
            $this->getParameters(true)
        ));
    }

    /**
     * @return string|null
     * @throws ReflectionException
     * @throws LoaderError
     * @throws SyntaxError
     */
    protected function resolveTemplate(): ?string
    {
        return $this->templateResolver->resolveShortcode($this);
    }
}
