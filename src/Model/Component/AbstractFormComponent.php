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

namespace NumberNine\Model\Component;

use NumberNine\Event\ContentEntityShowBeforeRenderEvent;
use NumberNine\Model\Component\Event\ComponentSupportedTemplatesEvent;
use NumberNine\Theme\TemplateResolverInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractFormComponent implements ComponentInterface, EventSubscriberInterface
{
    protected ?FormInterface $form = null;

    /** @var string[] */
    protected array $supportedTemplates = [];

    private EventDispatcherInterface $eventDispatcher;
    private TemplateResolverInterface $templateResolver;

    public static function getSubscribedEvents(): array
    {
        return [
            ContentEntityShowBeforeRenderEvent::class => 'processForm',
        ];
    }

    /**
     * @internal
     */
    public function processForm(ContentEntityShowBeforeRenderEvent $event): void
    {
        $found = false;

        foreach ($this->supportedTemplates as $template) {
            if (
                $event->getTemplate()->getTemplateName() ===
                $this->templateResolver->resolvePath($template)->getTemplateName()
            ) {
                $found = true;

                break;
            }
        }

        if (!$found) {
            return;
        }

        $request = $event->getRequest();

        $form = $this->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->handleSubmittedAndValidForm($event->getRequest());
            $event->setResponse($response);
        }
    }

    public function getTemplateParameters(): array
    {
        return [
            'form' => $this->getFormView(),
        ];
    }

    /**
     * @internal
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @internal
     */
    public function setTemplateResolver(TemplateResolverInterface $templateResolver): void
    {
        $this->templateResolver = $templateResolver;
    }

    /**
     * @internal
     */
    public function initialize(): void
    {
        /** @var ComponentSupportedTemplatesEvent $event */
        $event = $this->eventDispatcher->dispatch(
            new ComponentSupportedTemplatesEvent(static::class, $this->supportedTemplates)
        );

        $this->supportedTemplates = $event->getSupportedTemplates();
    }

    abstract protected function handleSubmittedAndValidForm(Request $request): Response;

    /**
     * This method should initialize `$this->form` if null, using FormFactoryInterface.
     */
    abstract protected function getForm(): FormInterface;

    protected function getFormView(): FormView
    {
        return $this->getForm()->createView();
    }
}
