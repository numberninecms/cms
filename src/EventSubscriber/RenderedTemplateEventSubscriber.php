<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\EventSubscriber;

use DOMDocument;
use DOMElement;
use NumberNine\Event\RenderedTemplateEvent;
use NumberNine\Model\Shortcode\ResponsiveShortcodeInterface;
use NumberNine\Theme\CssFramework\CssFrameworkInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class RenderedTemplateEventSubscriber implements EventSubscriberInterface
{
    private CssFrameworkInterface $cssFramework;

    public function __construct(CssFrameworkInterface $cssFramework)
    {
        $this->cssFramework = $cssFramework;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RenderedTemplateEvent::class => 'addResponsiveClasses'
        ];
    }

    public function addResponsiveClasses(RenderedTemplateEvent $event): void
    {
        /** @var $renderable ResponsiveShortcodeInterface */
        if (!($renderable = $event->getRenderable()) instanceof ResponsiveShortcodeInterface) {
            return;
        }

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($event->getTemplate(), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $responsiveVisibilityClasses = $this->cssFramework->getResponsiveVisibilityClasses($renderable->getVisibleViewSizes());

        foreach ($doc->childNodes as $childNode) {
            /** @var DOMElement $childNode */
            if ($childNode->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }

            $currentClasses = array_filter(explode(' ', $childNode->getAttribute('class')));
            $childNode->setAttribute('class', implode(' ', array_merge($currentClasses, $responsiveVisibilityClasses)));
        }

        $event->setTemplate((string)$doc->saveHTML());
    }
}
