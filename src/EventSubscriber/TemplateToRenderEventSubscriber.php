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

use NumberNine\Event\TemplateToRenderEvent;
use NumberNine\Model\General\Settings;
use NumberNine\Configuration\ConfigurationReadWriter;
use NumberNine\Theme\TemplateResolver;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class TemplateToRenderEventSubscriber implements EventSubscriberInterface
{
    private ConfigurationReadWriter $configurationReadWriter;
    private TemplateResolver $templateResolver;

    public function __construct(ConfigurationReadWriter $configurationReadWriter, TemplateResolver $templateResolver)
    {
        $this->configurationReadWriter = $configurationReadWriter;
        $this->templateResolver = $templateResolver;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TemplateToRenderEvent::class => [
                ['myAccountPage', 512],
            ],
        ];
    }

    public function myAccountPage(TemplateToRenderEvent $event): void
    {
        $pageForMyAccount = $this->configurationReadWriter->read(Settings::PAGE_FOR_MY_ACCOUNT);

        if ((int)$pageForMyAccount === $event->getEntity()->getId()) {
            $template = $this->templateResolver->resolvePath('page/my_account.html.twig');
            $event->setTemplate($template);
        }
    }
}
