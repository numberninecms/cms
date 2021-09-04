<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\EventSubscriber;

use NumberNine\Configuration\ConfigurationReadWriter;
use NumberNine\Content\PermalinkGenerator;
use NumberNine\Event\RegistrationFormSuccessEvent;
use NumberNine\Mailer\AddressFactory;
use NumberNine\Model\General\Settings;
use NumberNine\Model\General\SettingsDefaultValues;
use NumberNine\Repository\ContentEntityRepository;
use NumberNine\Theme\TemplateResolverInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;

final class RegistrationFormSuccessEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private MailerInterface $mailer, private AddressFactory $addressFactory, private TemplateResolverInterface $templateResolver, private ConfigurationReadWriter $configurationReadWriter, private PermalinkGenerator $permalinkGenerator, private ContentEntityRepository $contentEntityRepository)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RegistrationFormSuccessEvent::class => 'notifyUser'
        ];
    }

    public function notifyUser(RegistrationFormSuccessEvent $event): void
    {
        $user = $event->getUser();

        $appName = $this->configurationReadWriter->read(
            Settings::SITE_TITLE,
            SettingsDefaultValues::SITE_TITLE,
        );

        $email = (new TemplatedEmail())
            ->from($this->addressFactory->createApplicationAddress())
            ->to($user->getEmail())
            ->subject('Welcome to ' . $appName)
            ->htmlTemplate(
                $this->templateResolver->resolvePath('user/registration/email.html.twig')->getTemplateName()
            )
            ->context([
                'app_name' => $appName,
            ])
        ;

        $this->mailer->send($email);
    }
}
