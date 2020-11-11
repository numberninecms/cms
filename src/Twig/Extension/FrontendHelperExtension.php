<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Twig\Extension;

use NumberNine\Model\Admin\AdminController;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class FrontendHelperExtension extends AbstractExtension
{
    private ?Request $request;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_frontend', [$this, 'isFrontend']),
        ];
    }

    public function isFrontend(): bool
    {
        if (!$this->request) {
            return false;
        }

        $controller = explode('::', $this->request->get('_controller'))[0];
        $class = new ReflectionClass($controller);

        return $class->implementsInterface(AdminController::class) === false;
    }
}
