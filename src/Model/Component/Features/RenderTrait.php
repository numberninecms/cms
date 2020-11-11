<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Component\Features;

use NumberNine\Annotation\Shortcode\Exclude;
use NumberNine\Annotation\Shortcode\ExclusionPolicy;
use NumberNine\Annotation\Shortcode\Expose;
use NumberNine\Event\RenderedTemplateEvent;
use NumberNine\Event\RenderEvent;
use NumberNine\Model\Rendering\TwigTrait;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function Symfony\Component\String\u;

trait RenderTrait
{
    use TwigTrait;
    use ToolboxTrait;
    use EventDispatcherTrait;
    use RenderableInspectorTrait;
    use TemplateResolverTrait;

    private string $templateName;

    /**
     * @return string
     * @throws ReflectionException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    final public function render(): string
    {
        $values = $this->getValues();

        /** @var RenderEvent $renderEvent */
        $renderEvent = $this->eventDispatcher->dispatch(new RenderEvent($this, $values));
        $values = $renderEvent->getValues();

//        todo: do something with unknown parameters (maybe a tooltip warning?)
//
//        $unknownParameters = '';
//
//        if (method_exists($this, 'getUnknownParameters') && !empty($this->getUnknownParameters())) {
//            $unknownParameters = $this->twig->render('@NumberNine/alerts/properties_missing.html.twig', [
//                'class' => get_class($this),
//                'properties' => $this->getUnknownParameters(),
//            ]);
//        }

        $templateName = $this->templateName ?? $this->resolveTemplate();
        $renderedTemplate = $templateName ? $this->twig->render($templateName, $values) : '';

        /** @var RenderedTemplateEvent $renderedTemplateEvent */
        $renderedTemplateEvent = $this->eventDispatcher->dispatch(new RenderedTemplateEvent($this, $renderedTemplate));

        return $renderedTemplateEvent->getTemplate();
    }

    /**
     * @param bool $isSerialization
     * @return array
     * @throws ReflectionException
     */
    final protected function getValues(bool $isSerialization = false): array
    {
        $inspectedRenderable = $this->renderableInspector->inspect($this, $isSerialization);
        $values = $inspectedRenderable->getExposedValues();

        foreach (static::injectThemeOptions() as $variable => $themeOption) {
            $values[is_string($variable) ? $variable : $themeOption] = $this->toolbox->getThemeOption($themeOption);
        }

        foreach (static::injectSettings() as $variable => $setting) {
            $values[is_string($variable) ? $variable : $setting] = $this->toolbox->getSetting($setting);
        }

        return $values;
    }

    /**
     * @param string $templateName
     */
    final public function setTemplateName(string $templateName): void
    {
        $this->templateName = $templateName;
    }
}
