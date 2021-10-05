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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractFormComponent implements ComponentInterface, EventSubscriberInterface
{
    protected ?FormInterface $form = null;

    /** @var string[] */
    protected array $supportedTemplates = [];

    public function __construct(
        private FormFactoryInterface $formFactory,
        private EventDispatcherInterface $eventDispatcher,
    ) {
        /** @var ComponentSupportedTemplatesEvent $event */
        $event = $eventDispatcher->dispatch(new ComponentSupportedTemplatesEvent(
            static::class,
            $this->supportedTemplates,
        ));

        $this->supportedTemplates = $event->getSupportedTemplates();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContentEntityShowBeforeRenderEvent::class => 'processForm',
        ];
    }

    public function processForm(ContentEntityShowBeforeRenderEvent $event): void
    {
        if (!\in_array($event->getTemplate()->getTemplateName(), $this->supportedTemplates, true)) {
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

    abstract protected function handleSubmittedAndValidForm(Request $request): Response;

    /**
     * This method should initialize `$this->form` if null, using `$this->formFactory`.
     */
    abstract protected function getForm(): FormInterface;

    protected function getFormView(): FormView
    {
        return $this->getForm()->createView();
    }
}
