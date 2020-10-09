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

use InvalidArgumentException;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\Translation\QuickTranslate;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookup;
use Symfony\WebpackEncoreBundle\Exception\EntrypointNotFoundException;
use Twig\Environment;

/**
 * Class ExceptionSubscriber
 * @package NumberNine\EventSubscriber
 */
final class ExceptionEventSubscriber implements EventSubscriberInterface
{
    use QuickTranslate;

    private Environment $twig;
    private SessionInterface $session;
    private TranslatorInterface $translator;

    /**
     * @param Environment $twig
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     */
    public function __construct(Environment $twig, SessionInterface $session, TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->session = $session;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => 'handleEntrypointNotFoundException',
        ];
    }

    /**
     * Handles webpack's EntrypointNotFoundException or InvalidArgumentException when an entrypoint is unidentified.
     *
     * Two scenarios:
     *      1) Admin : displays error
     *      2) Frontend : redirects to the admin updates page to let the admin user rebuild from the web
     *
     * @param ExceptionEvent $event
     * @throws ReflectionException
     */
    public function handleEntrypointNotFoundException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        while ($exception && !($exception instanceof EntrypointNotFoundException || $exception instanceof InvalidArgumentException)) {
            $exception = $exception->getPrevious();
        }

        if (!$exception) {
            return;
        }

        if ($exception instanceof InvalidArgumentException) {
            $firstLine = $exception->getTrace()[0] ?? null;
            if (!($firstLine && $firstLine['class'] === EntrypointLookup::class)) {
                return;
            }
        }

        [$controller] = explode('::', $event->getRequest()->get('_controller'));
        $class = new ReflectionClass($controller);

        if ($class->implementsInterface(AdminController::class)) {
            if (!$event->getRequest()->get('show_exception')) {
                $errorPage = $this->twig->render(
                    'core/error/webpack_exception.html.twig',
                    [
                        'status_code' => 500,
                        'status_text' => $exception->getMessage()
                    ]
                );

                $response = new Response($errorPage, 500);
                $event->setResponse($response);
            }
            return;
        }

        $this->session->getFlashBag()->add('notice', $this->__('Some of your theme assets are missing. Rebuild them to restore your frontend.'));
        //$event->setResponse(new RedirectResponse($this->container->get('router')->generate('admin_update')));
    }
}
