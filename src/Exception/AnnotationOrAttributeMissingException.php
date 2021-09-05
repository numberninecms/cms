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

namespace NumberNine\Exception;

final class AnnotationOrAttributeMissingException extends \LogicException
{
    public function __construct(string $missingType, ?string $objectClassName = null)
    {
        if (!$objectClassName) {
            parent::__construct(sprintf('Annotation or attribute of type "%s" is missing.', $missingType));
        }

        parent::__construct(sprintf(
            'Annotation or attribute of type "%s" is missing on "%s" class.',
            $missingType,
            $objectClassName,
        ));
    }
}
