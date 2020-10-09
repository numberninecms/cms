<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestAnalyzer
{
    private ?Request $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getMasterRequest();
    }

    public function isPreviewMode(): bool
    {
        if (!$this->request) {
            return false;
        }

        return $this->request->get('n9') === 'admin';
    }

    public function isHomePage(): bool
    {
        if (!$this->request) {
            return false;
        }

        $route = $this->request->attributes->get('_route');
        return in_array($route, ['numbernine_homepage', 'numbernine_homepage_page']);
    }
}
