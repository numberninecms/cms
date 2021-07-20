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

namespace NumberNine\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class SupportedContentEntityRelationshipsEvent extends Event
{
    private string $className;

    /** @var string[] */
    private array $relationships = [];

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getRelationships(): array
    {
        return $this->relationships;
    }

    public function setRelationships(array $relationships): self
    {
        $this->relationships = $relationships;

        return $this;
    }

    public function addRelationship(string $string): self
    {
        if (!in_array($string, $this->relationships)) {
            $this->relationships[] = $string;
        }

        return $this;
    }

    public function removeRelationship(string $string): self
    {
        if (in_array($string, $this->relationships)) {
            unset($this->relationships[array_search($string, $this->relationships)]);
        }

        return $this;
    }
}
