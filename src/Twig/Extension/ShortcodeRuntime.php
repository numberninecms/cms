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

use Exception;
use NumberNine\Content\ShortcodeProcessor;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Extension\RuntimeExtensionInterface;

final class ShortcodeRuntime implements RuntimeExtensionInterface
{
    private ShortcodeProcessor $shortcodeProcessor;
    private AuthorizationCheckerInterface $authorizationChecker;
    private string $environment;

    public function __construct(ShortcodeProcessor $shortcodeProcessor, AuthorizationCheckerInterface $authorizationChecker, string $environment)
    {
        $this->shortcodeProcessor = $shortcodeProcessor;
        $this->authorizationChecker = $authorizationChecker;
        $this->environment = $environment;
    }

    public function renderShortcode(string $text): string
    {
        try {
            return $this->shortcodeProcessor->applyShortcodes($text);
        } catch (Exception $e) {
            if ($this->environment === 'dev' && $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
                throw $e;
            }

            return $text;
        }
    }
}
